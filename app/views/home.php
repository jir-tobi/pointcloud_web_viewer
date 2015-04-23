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
      Each object's point cloud and mesh can be viewed live.<br>
      The model ZIP-files to download contain:
      <ul>
          <li>PCD point cloud</li>
          <li>PLY mesh</li>
          <li>PNG preview image</li>
          <li>Gazebo model folder</li>
      </ul>
      The raw-data ZIP-files to download contain:
      <ul>
          <li>PCD point clouds</li>
          <li>PNG RGB images</li>
          <li>PNG depth images</li>
          <li>DAT camera infos</li>
      </ul>
    </div>

    <div class="col-lg-2 col-lg-offset-2">
      <div style="width:100%; height: 20px;"></div>
      <a title="Jacobs Robotics" href="http://robotics.jacobs-university.de"><img class="img-responsive" src="img/logo.png" width="250"></a>
    </div>

  </div>
  
	  Importing the raw data into your application:
      <div class="panel panel-default">
        <div class="panel-heading">
          <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Color Image</button>
        </div>
        <div class="panel-collapse collapse out">
          <div class="panel-body">
            <p>Importing the color image:
<pre><code>cv::Mat3b color_image = cv::imread("rgb_0001.png");</code></pre>
			</p>
          </div>
        </div>
        
        <div class="panel-heading">
          <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Depth Image</button>
        </div>
        <div class="panel-collapse collapse out">
          <div class="panel-body">
            <p>Importing the depth image:
<pre><code>cv::Mat to_convert = cv::imread("d_0001.png", CV_LOAD_IMAGE_ANYDEPTH);
cv::Mat converted;
to_convert.convertTo(converted, CV_32FC1);
cv::Mat ones = cv::Mat::ones(converted.size(), converted.depth());
cv::Mat1d depth_image = converted.mul(ones,1/5000);</code></pre>
			</p>
          </div>
        </div>
        
        <div class="panel-heading">
          <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Point Cloud</button>
        </div>
        <div class="panel-collapse collapse out">
          <div class="panel-body">
            <p>Importing the point cloud:
<pre><code>pcl::PointCloud&lt;pcl::PointXYZRGB&gt;::Ptr pc(new pcl::PointCloud&lt;pcl::PointXYZRGB&gt;);
if (pcl::io::loadPCDFile&lt;pcl::PointXYZRGB&gt;("pc_0001.pcd", *pc) == -1) {
    ROS_ERROR("Unable to read pc_0001.pcd!");
}</code></pre>
			</p>
          </div>
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
          $zipFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . $dirInfo->getFilename() . '.zip';
          $rawFile = DATAFOLDER . '/' . $dirInfo->getFilename() . '/' . $dirInfo->getFilename() . '-raw.zip';

          $pcSize = round(filesize($pcFile) / (1000000));
          $zipSize = round(filesize($zipFile) / (1000000));
          $rawSize = round(filesize($rawFile) / (1000000));

          ?>
          <div class="col-lg-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
              <div class="caption">
                <h4><?php echo $title ?></h4>
                <div style="text-align:left; padding:5px;">
                  <h5><?php echo $desc ?></h5>
                  Point Cloud (view): <?php echo $pcSize ?> MB<br>
                  <?php if (file_exists($zipFile)) { ?>Model: <?php echo $zipSize ?> MB<?php } ?>
                  <?php if (file_exists($rawFile)) { ?>, Raw Data: <?php echo $rawSize ?> MB<?php } ?>
                </div>
                <p><a class="btn btn-sm btn-primary" href="view/<?php echo $folderName ?>">View</a>
                <?php if (file_exists($zipFile)) { ?>
                <a class="btn btn-sm btn-success" href="<?php echo $zipFile ?>">Model</a>
				<?php } ?>
                <?php if (file_exists($rawFile)) { ?>
				<a class="btn btn-sm btn-success" href="<?php echo $rawFile ?>">Raw Data</a></p>
				<?php } ?>
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
