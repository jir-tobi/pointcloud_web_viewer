  <?php
  include('header.php');
  ?>

  <script type="text/javascript">
  $( document ).ready(function() {
      $("[rel='tooltip']").tooltip();

      $('.thumbnail').hover(
          function(){
              $(this).find('.caption').slideDown(350); //.fadeIn(250)
          },
          function(){
              $(this).find('.caption').slideUp(250); //.fadeOut(205)
          }
      );
  
      $(".spoiler-trigger").click(function() {
	      $(this).parent().next().collapse('toggle');
	  });

      $("#buttonShow").click(function() {
	    $("#intro").toggleClass('hide');
	    $("#introul").toggleClass('hide');
            $(this).toggleClass('active');
      });

  });
  
  </script>

  <!-- Top -->
  <div class="row">

    <div class="col-lg-8">
      <h1 style="font-size:38px;">Jacobs Robotics Watertight Meshes Dataset</h1><br />
      <p class="lead">Point clouds, mesh representations and Gazebo models of everyday objects.</p>

      <button type="button" class="btn btn-primary btn-lg" id="buttonShow">Show detailed explanation</button>

      <p class="text hide" id="intro">
      <br>
      The object point clouds available on this website were created from low-cost RGBD sensor data without user interaction, see our <a href="http://dx.doi.org/10.1016/j.robot.2015.01.005" target="_blank">publication</a>: <i>Mihalyi et al. (Robotics and Autonomous Systems, April 2015): Robust 3D Object Modeling with a Low-Cost RGBD-Sensor and AR-Markers for Applications with Untrained End-Users</i>.
      <br><br>
      From these point clouds, watertight mesh representations were generated as described in <i>Fromm et al. (submitted to ICRA 2016): Unsupervised Watertight Mesh Generation From Noisy Free-Form RGBD Object Models Using Growing Neural Gas</i>.
      <br><br>
      Each object's point cloud and mesh can be viewed live. We provide zipped .bag files for the objects which were used in our modeling approach as well as .zip files containing:

      <ul class="text hide" id="introul">
          <li>PCD point cloud</li>
          <li>PLY mesh</li>
          <li>PNG preview image</li>
          <li>Gazebo model folder</li>
      </ul>
      </p>  
    </div>
    
    <div class="col-lg-2 col-lg-offset-2">
      <div style="width:100%; height: 20px;"></div>
      <a title="Jacobs Robotics" href="http://robotics.jacobs-university.de"><img class="img-responsive" src="img/logo.png" width="250"></a>
    </div>

  </div>

  <!-- Separator -->
  <div class="row" style="text-align:center;">
    <div class="col-lg-12 col-sm-12 col-xs-12">
      <hr>
    </div>
  </div>

  <!-- Showcase -->
  <div class="row">
  <?php
  $files_array = array();
  foreach (new DirectoryIterator(DATAFOLDER) as $dirInfo) {
    if($dirInfo->isDir() && !$dirInfo->isDot()) {
      // Info file
      $pcFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . PCFILE;
      $infoFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . PCINFO;
      if (file_exists($pcFile) && file_exists($infoFile)) {
        // sort key, can also be a timestamp or something alike
        $key = $dirInfo->getFilename();
        $data = $dirInfo->getFilename();
        $files_array[$key] = $data;
	  }
    }
  }
  ksort($files_array);

  foreach($files_array as $key => $file){

      $pcFile = DATAFOLDER . '/' . $file . '/' . PCFILE;
      $infoFile = DATAFOLDER . '/' . $file . '/' . PCINFO;
      $zipFile = DATAFOLDER . '/' . $file . '/' . $file . '.zip';
      $bagFile = DATAFOLDER . '/' . $file . '/' . $file . '.bag.zip';

        // Read the info
        $folderName = $file;
        $fi = fopen($infoFile, 'r');
        $title = fgetcsv($fi);
        $meta = fgetcsv($fi);
        fclose($fi);

        // Sanity check
        if (sizeof($title) == 2) {
          $title = $title[1];

          $desc = '';
          if (sizeof($meta) == 2) {
            $desc = $meta[1];
          }
          $imgFile = DATAFOLDER . '/' . $file . '/' . PCIMG;
          if (!file_exists($imgFile)) {
            $imgFile = 'img/default.png';
          }

          $pcSize = round(filesize($pcFile) / (1000000));
          $zipSize = round(filesize($zipFile) / (1000000));
          $bagSize = round(filesize($bagFile) / (1000000000),2);

          ?>
          <div class="col-lg-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
              <div class="caption">
                <h4><?php echo $title ?></h4>
                <div style="text-align:left; padding:5px;">
                  <h5><?php echo $desc ?></h5>
                  Point Cloud (view): <?php echo $pcSize ?> MB<br>
                  <?php if (file_exists($zipFile)) { ?>Model: <?php echo $zipSize ?> MB<?php } ?><?php if (file_exists($bagFile)) { ?>, Bagfile: <?php echo $bagSize ?> GB<?php } ?>
                </div>
                <p><a class="btn btn-sm btn-primary" href="view/<?php echo $file ?>">View</a>
                <?php if (file_exists($zipFile)) { ?>
                <a class="btn btn-sm btn-success" href="<?php echo $zipFile ?>">Model</a>
				<?php } ?>
                <?php if (file_exists($bagFile)) { ?>
				<a class="btn btn-sm btn-success" href="<?php echo $bagFile ?>">Bagfile</a></p>
				<?php } ?>
              </div>
              <img class="img-responsive" src="<?php echo $imgFile ?>">
            </div>
          </div>
          <?php
        }
  }
  ?>
  </div>

  <?php
  include('footer.php');
  ?>
