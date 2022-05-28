#pragma once

#include <cstdlib>
#include "OpenGP/GL/Application.h"

using namespace OpenGP;

inline float lerp(float x, float y, float t) {

    // Linear interpolation between x and y
    float interpolate = x + t * (y - x);
    return interpolate;
}

inline float fade(float t) {
    // Quintic interpolation curve
    return t * t * t * (t * (t * 6 - 15) + 10);

    // Cubic interpolation curve
    //return t * t * (3.0f - 2.0f * t);
}

inline float rand01() {
    return ((float) std::rand())/((float) RAND_MAX);
}

float* perlin2D(const int width, const int height, const int period=64);

// Generates a heightmap using regular fBm (fractional brownian motion)
R32FTexture* fBm2DTexture() {

    // Precompute perlin noise on a 2D grid
   
    const int width = 2048;
    const int height = 2048;
    float *perlin_data = perlin2D(width, height, 512);

    
    // fBm parameters - Summer
    float H = 0.9f;
    float lacunarity = 2.0f;
    float offset = 0.1f;
    const int octaves = 5;

    // Initialize to 0s
    float *noise_data = new float[width * height];
    for (int i = 0; i < width; ++ i) {
        for (int j = 0; j < height; ++ j) {
            noise_data[i + j * height] = 0;
        }
    }

    // Precompute exponent array
    float *exponent_array = new float[octaves];
    float f = 1.0f;
    for (int i = 0; i < octaves; ++i) {
        exponent_array[i] = std::pow(f, -H);
        f *= lacunarity;
    }

    for (int i = 0; i < width; ++ i) {
        for (int j = 0; j < height; ++ j) {

            // TODO: Index (for use in inner loop - frequency)) -I & J
            int I = i;
            int J = j;

            for(int k = 0; k < octaves; ++k) {

                // TODO: Generate perlin value
                float noise = exponent_array[k] * perlin_data[(I % width) + (J % height) * height ] + offset;

                // TODO: Generate noise value
                noise_data[i + j * height] += noise;
                
                // TODO: Point to sample at next octave
                I *= lacunarity;
                J *= lacunarity;
            }
        }
    }

    R32FTexture* _tex = new R32FTexture();
    _tex->upload_raw(width, height, noise_data);
    //_tex->download("image.png");
    
    // Clean up
    delete perlin_data;
    delete noise_data;
    delete exponent_array;

    return _tex;
}

// Generates a height map using Hybrid Multifractal fBM (fractional Brownian motion)[optional]
R32FTexture* HybridMultifractal2DTexture() {

    // Precompute perlin noise on a 2D grid
    const int width = 2048;
    const int height = 2048;
    float *perlin_data = perlin2D(width, height, 512);

    // fBm parameters - Lunar
    float H = 1.0f;
    float lacunarity = 4.0f;
    float offset = 0.2f;
    const int octaves = 16;

    // Initialize to 0s
    float *noise_data = new float[width * height];
    for (int i = 0; i < width; ++ i) {
        for (int j = 0; j < height; ++ j) {
            noise_data[i + j * height] = 0.0f;
        }
    }

    // Precompute exponent array
    float *exponent_array = new float[octaves];
    float f = 1.0f;
    for (int i = 0; i < octaves; ++i) {
        exponent_array[i] = pow( f, -H);
        f *= lacunarity;
    }

    for (int i = 0; i < width; ++i) {
        for (int j = 0; j < height; ++j) {

            // TODO: Index (for use in inner loop - frequency)
            

            // TODO: Generate Perlin value (Hybrid Multifractal (1 - abs(perlin)))
            int perlin = 0;

            //TODO:  Point to sample at next octave
            

            for (int k = 1; k < octaves; k++) {

                // TODO: Restrict weight to be less than one (guard against divergence)
                

                // TODO:Generate Perlin value

                // TODO: Add weighted Perlin value to Perlin
                
                // TODO: Adjust weighting value
               
                // TODO: Point to sample at next octave
                
            }

            // Generate noise value
            int noiseIndex = i + j * height;
            noise_data[noiseIndex] = perlin;
        }
    }

    R32FTexture* _tex = new R32FTexture();
    _tex->upload_raw(width, height, noise_data);

    // Clean up
    delete perlin_data;
    delete noise_data;
    delete exponent_array;

    return _tex;
}

float* perlin2D(const int width, const int height, const int period) {

    // Precompute random gradients
    float *gradients = new float[width * height * 2];
    auto sample_gradient = [&](int i, int j) {
        float x = gradients[2 * (i + j * height)];
        float y = gradients[2 * (i + j * height) + 1];
        return Vec2(x,y);
    };

    for (int i = 0; i < width; ++ i) {
        for (int j = 0; j < height; ++ j) {
            float angle = rand01();
            gradients[2 * (i + j * height)] = cos(2 * angle * M_PI);
            gradients[2 * (i + j * height) + 1] = sin(2 * angle * M_PI);
        }
    }

    // Perlin Noise parameters
    float frequency = 1.0f / period;

    float *perlin_data = new float[width*height];
    for (int i = 0; i < width; ++ i) {
        for (int j = 0; j < height; ++ j) {

            // Integer coordinates of corners
            int left = (i / period) * period;
            int right = (left + period) % width;
            int top = (j / period) * period;
            int bottom = (top + period) % height;

            // local coordinates [0,1] within each block
            float dx = (i - left) * frequency;
            float dy = (j - top) * frequency;



            // Fetch random vectors at corners
            Vec2 topleft = sample_gradient(left, top);
            Vec2 topright = sample_gradient(right, top);
            Vec2 bottomleft = sample_gradient(left, bottom);
            Vec2 bottomright = sample_gradient(right, bottom);



            //Vector from each corner to pixel center
            Vec2 a(dx, -dy); // topleft
            Vec2 b(dx - 1, -dy); // topright
            Vec2 c(dx, 1 - dy); // bottomleft
            Vec2 d(dx - 1, 1 - dy); // bottomright



            // Get scalars at corners
            float s = a.dot(topleft);
            float t = b.dot(topright);
            float u = c.dot(bottomleft);
            float v = d.dot(bottomright);



            // Interpolate along x
            float st = lerp(s, t, fade(dx));
            float uv = lerp(u, v, fade(dx));



            // Interpolate along y
            float noise = lerp(st, uv, fade(dy));



            perlin_data[i + j * height] = noise;
            /*TODO: noise generation
            float xCoord = i * frequency;
            float yCoord = j * frequency;
            float zCoord = 0;
            // TODO: Unit cube vertex coordinates surrounding the sample point. In this example find the unit square vertex coordinates in X and Y coords. (Weightage-10%)
            int x0 = int(floor(xCoord));
            int x1 = x0 + 1;
            int y0 = int(floor(yCoord));
            int y1 = y0 + 1;
            int z0 = int(floor(zCoord));
            int z1 = z0 + 1;

            // Determine sample point position within unit cube (unit square in our case) (Weightage-10%)
            float pointx0 = xCoord - float(x0);
            float pointx1 = pointx0 - 1.0f;
            float pointy0 = yCoord - float(y0);
            float pointy1 = pointy0 - 1.0f;
            float pointz0 = zCoord - float(z0);
            float pointz1 = pointz0 - 1.0f;

            //Construct distance vectors from each corner to the sample point (Weightage-10%)	
            Vec2 v00(pointx0, pointy0);
            Vec2 v01(pointx0, pointy1);
            Vec2 v10(pointx1, pointy0);
            Vec2 v11(pointx1, pointy1);

            //Fetch the gradients at corners (Weightage-10%)
            //int index00 = P[(P[x0 & 255] + y0 & 255) & 255];
            int index00 = gradients[bottom];
            int index10 = gradients[right];
            int index01 = gradients[left];
            int index11 = gradients[top];
            Vec2 g00 = sample_gradient(pointx0, pointy0);
            Vec2 g01 = sample_gradient(pointx0, pointy1);
            Vec2 g10 = sample_gradient(pointx1, pointy0);
            Vec2 g11 = sample_gradient(pointx1, pointy1);

            //TODO: Get scalars at corners HINT: take the dot product of gradient and the distance vectors. (Weightage-10%)
            float dotX0Y0Z0, dotX0Y0Z1, dotX0Y1Z0, dotX0Y1Z1, dotX1Y0Z0, dotX1Y0Z1, dotX1Y1Z0, dotX1Y1Z1;
            dotX0Y0Z0 = v00.dot(g00);
            //dotX0Y0Z1 = v00.dot(g01);
            dotX0Y1Z0 = v01.dot(g01);
            //dotX0Y1Z1 = v00.dot(g);
            dotX1Y0Z0 = v10.dot(g10);
            //dotX1Y0Z1 = v00.dot(g00);
            dotX1Y1Z0 = v11.dot(g11);
            //dotX1Y1Z1 = v00.dot(g00);

            //TODO: Interpolate along X (Weightage-5%)
            float st = lerp(dotX0Y0Z0, dotX1Y0Z0, fade(pointx0));

            //TODO: Interpolate along Y (Weightage-5%)
            float uv = lerp(dotX0Y1Z0, dotX1Y1Z0, fade(pointx0));
            float noise = lerp(st, uv, fade(pointy0));
            
            
            perlin_data[i + j * height] = noise;
            */
        }
    }

    delete gradients;
    return perlin_data;
}
