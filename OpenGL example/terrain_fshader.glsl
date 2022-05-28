R"(
#version 330 core

// Uniforms
uniform sampler2D noiseTex;
uniform sampler2D grass;
uniform sampler2D rock;
uniform sampler2D sand;
uniform sampler2D snow;
uniform sampler2D water;
///uniform sampler2D lunar;

uniform float waveMotion;
uniform vec3 viewPos;

// In
in vec2 uv;
in vec3 fragPos;
in float waterHeight;

// Out
out vec4 color;

void main() {

    // Directional light source
    vec3 lightDir = normalize(vec3(1,1,1));

    // Texture size in pixels
    ivec2 size = textureSize(noiseTex, 0);

    /// TODO: Calculate surface normal N
    /// HINT: Use textureOffset(,,) to read height at uv + pixelwise offset
    /// HINT: Account for texture x,y dimensions in world space coordinates (default f_width=f_height=5)
    vec3 A = vec3(uv.x + 1.0f / size.x, uv.y, textureOffset(noiseTex, uv, ivec2(-1, 0)));
    vec3 B = vec3(uv.x - 1.0f / size.x, uv.y, textureOffset(noiseTex, uv, ivec2(1, 0)));
    vec3 C = vec3(uv.x  / size.x, (uv.y - 1) / size.y, textureOffset(noiseTex, uv, ivec2(0, -1)));
    vec3 D = vec3(uv.x  / size.x, (uv.y + 1) / size.y, textureOffset(noiseTex, uv, ivec2(0, 1)));
    vec3 N = normalize( cross(normalize(A-B), normalize(C-D)) );

    /// TODO: Texture according to height and slope
    /// HINT: Read noiseTex for height at uv
    ///float rock_face = 0.0;
    float snow_peak = 5.0;
	float grass_peak = 2.5;
	float sand_peak =	1.0;
/*
      
if (fragPos.y > snow_peak) {
color = texture(snow, uv);
}
else if (fragPos.y > grass_peak && fragPos.y < snow_peak) {
float halfDistance = (snow_peak + grass_peak) / 2.0f;
if (fragPos.y < (grass_peak + halfDistance)) {


float pos = fragPos.y - grass_peak;
float posScaled = pos / halfDistance;
color = (texture(rock, uv) * (posScaled))+(texture(grass, uv) * (1 - posScaled));}
else {
float pos = snow_peak - fragPos.y;
float posScaled = pos / halfDistance;
color = (texture(snow, uv) * (1 - posScaled))+(texture(rock, uv) * (posScaled));}
} else if (fragPos.y > sand_peak && fragPos.y < grass_peak) {
float halfDistance = (sand_peak + grass_peak) / 2.0f;
if (fragPos.y < (grass_peak + halfDistance)) {


float pos = sand_peak - fragPos.y;
float posScaled = pos / halfDistance;
color = (texture(grass, uv) * (1 - posScaled))+(texture(snow, uv) * (posScaled));}}
else if (fragPos.y < sand_peak && fragPos.y > waterHeight) {
color = texture (sand, uv);}
    /// TODO: Calculate ambient, diffuse, and specular lighting
    /// HINT: max(,) dot(,) reflect(,) normalize()

    float ambient = 0.05f;
    float diffuse_coefficient = 0.2f;
    float specular_coefficient = 0.2f;
    float specularPower = 16.0;
    // Calculate diffuse lighting factor
    float diffuse = diffuse_coefficient * max(0.0f, -dot(N, lightDir));
    // Calculate specular lighting factors
    vec3 view_direction = normalize(viewPos - fragPos);
    vec3 halfway = normalize(lightDir + view_direction);
    float specular = specular_coefficient * max(0.0f, pow(dot(N, halfway), specularPower));
	
    
    //vec4 col *= (ambient + diffuse + specular);  
    color[0] *= (ambient + diffuse + specular);
    color[1] *= (ambient + diffuse + specular);
    color[2] *= (ambient + diffuse + specular); */

    color = vec4(1,0,0,1);
}
)"
