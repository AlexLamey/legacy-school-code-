<!--my_business.php-->



<?php include "common/document_head.html" ?>

<main class="w3-container" onload = "carousel()">

<?php include "common/banner.php" ?>

<?php include "common/menus.html" ?>

      <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey"
           style="padding-right:0">
        <article class="w3-half">
          <h3>
            You've come to Lynn Mountain Meadows!
          </h3>
          <p>
            Founded in 2021 by co-founders Danielle Brown and Ashlynn Brownell, 
	    Lynn Mountain Meadows strives to show the world the 
	    history of rural Nova Scotia through the preservation 
	    and documentation of authentic relics of the past. 
            For those more interested in the present, the farm also prides itself 
            on the quality of their fresh quail eggs and quality
            lowbrush blueberries.

          </p>
          <p>
            Whether your interest is in tours, demonstrations 
	    or simply enjoying fresh farm food... check out our e-store!
          </p>
        </article>
        <!-- <div class="w3-half w3-padding w3-center">
          <div class="w3-card-4 w3-section">
            <img id="placeholder" alt="Picture of a clothesline wheel"
                 src="submissions/submission01/images/products/wheel.jpg"
                 class="homePageImage w3-image">
            <footer class="w3-container w3-blue">
              <h5>A clothesline wheel</h5>
            </footer>
          </div>
        </div> --
      </div> -->
      <?php include "resources/images_and_labels.html" ?>

<script>
var index = 0;
carousel_2();

function carousel_2() {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var labels = document.getElementsByClassName("myLabels");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
    labels[i].style.display = "none";
  }
  index++;
  if (index > slides.length) {index = 1}
  slides[index-1].style.display = "block";
  labels[index-1].style.display = "block";
  setTimeout(carousel_2, 3000); // Change image every 3 seconds
}</script>
    </main>
 
    
    <?php include "common/footer.html" ?>

