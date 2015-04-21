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
  });
  </script>

  <!-- Top -->
  <div class="row">

    <div class="col-lg-8">
      <h1 style="font-size:40px;">Jacobs Robotics Object Database</h1><br />
      <p class="lead">Point clouds, mesh representations and Gazebo models of everyday objects.</p>
      
      The object point clouds available on this website were created from low-cost RGBD sensor data without user interaction, see our <a href="http://dx.doi.org/10.1016/j.robot.2015.01.005" target="_blank">publication</a>: <i>Mihalyi et al. (Robotics and Autonomous Systems, April 2015): Robust 3D Object Modeling with a Low-Cost RGBD-Sensor and AR-Markers for Applications with Untrained End-Users</i>.
      <br><br>
      From these point clouds, watertight mesh representations were generated as described in <i>Fromm et al. (submitted to IROS 2015): Unsupervised Watertight Mesh Generation From Noisy Free-Formed RGBD Object Models Using Growing Neural Gas</i>.
      <hr>
      Each object's point cloud and mesh can be viewed live. The ZIP-files to download contain:
      <ul>
          <li>PCD point cloud</li>
          <li>PLY mesh</li>
          <li>PNG preview image</li>
          <li>Gazebo model folder</li>
      </ul>
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
  foreach (new DirectoryIterator(DATAFOLDER) as $dirInfo) {
    if($dirInfo->isDir() && !$dirInfo->isDot()) {
      // Info file
      $pcFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . PCFILE;
      $infoFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . PCINFO;

      if (file_exists($pcFile) && file_exists($infoFile)) {

        // Read the info
        $folderName = $dirInfo->getFilename();
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
          $imgFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . PCIMG;
          if (!file_exists($imgFile)) {
            $imgFile = 'img/default.png';
          }
          $dataFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . $dirInfo->getFilename() . '.zip';

          $pcSize = round(filesize($pcFile) / (1000000));
          $dataSize = round(filesize($dataFile) / (1000000));

          ?>
          <div class="col-lg-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
              <div class="caption">
                <h4><?php echo $title ?></h4>
                <div style="text-align:left; padding:5px;">
                  <h5><?php echo $desc ?></h5>
                  <h5>Point Cloud Size: <?php echo $pcSize ?>MB<br>
                  ZIP File Size: <?php echo $dataSize ?>MB</h5>
                </div>
                <p><a class="btn btn-sm btn-primary" href="view/<?php echo $folderName ?>">View</a>
                <a class="btn btn-sm btn-success" href="<?php echo $dataFile ?>">Download</a></p>
              </div>
              <img class="img-responsive" src="<?php echo $imgFile ?>">
            </div>
          </div>
          <?php
        }
      }
    }
  }
  ?>
  </div>

  <?php
  include('footer.php');
  ?>
