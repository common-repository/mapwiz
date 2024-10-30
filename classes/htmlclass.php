<?php

	class htmlClass
	{
		public function menu(){
		?>
            <nav class="menu-bar">
              <ul class="panel-openClose">
                <li class="menu-btn">
                  <div class="menu-icon-container">
                    <div class="menu-icon"></div>
                  </div>
                </li>
              </ul>
              <ul class="panel-tabs">
                <li class="styler-btn" data-related-section="#styler-section"><a href="javascript:void(0)" title="Add Styler"></a> </li>
                <li class="marker-btn" data-related-section="#marker-section"><a href="javascript:void(0)" title="Markers"></a> </li>
                <li class="map-btn" data-related-section="#featuredmap-section"><a href="javascript:void(0)" title="Featured Maps"></a> </li>
                <li class="settings-btn" data-related-section="#setting-section"><a href="javascript:void(0)" title="Controls and Settings "></a> </li>
              </ul>
              <ul class="search-manu">
                <li class="search-btn"> <a href="javascript:void(0)" title="Search your location"></a>
                  <div class="sreach-wrp">
                    <input type="text" id="geoSearch" name="geoSearch" class="geo-earch" placeholder="Search your location" />
                  </div>
                </li>
              </ul>
            </nav>
		<?php
		}
		
		public function operation($id = 0){?>
            <div class="top-link ">
              <ul class="clearfix">
                <li> <a href="admin.php?page=myplug/muyplg.php&mid">New Map</a> </li>
                <li> <a onClick="jQuery('#SaveMap').val(1);jQuery('#frmMAP').submit();">Save &amp; Exit</a> </li>
                <li> <a onClick="jQuery('#SaveMap').val(2);jQuery('#frmMAP').submit();">Save</a> </li>
                <li class="dropdown"> <a href="javascript.void(0);"></a>
                  <ul>
                    <li> <a href="javascript.void(0);"></a>
                      <ul>
                        <?php if ($id > 0){?>
                        <li><a onclick="return confirm('Are you sure?')" href="admin.php?page=myplug/muyplg.php&info=del&id=<?=$id;?>">delete map</a></li>
                        <?php }?>
                        <li><a href="admin.php?page=myplug/muyplg.php&mid<?php if ($id){echo "=".$id;}?>">default reset</a></li>
                        <li><a href="http://mapwiz.io/faq/Helping-guide/" target="_blank">helping guide</a></li>
                        <li><a href="http://mapwiz.io/#contactform" target="_blank">report bug</a></li>
                        <li><a href="http://mapwiz.io" target="_blank">about mapwiz</a></li>
                        <li><a href="http://mapwiz.io/#contactform" target="_blank">contact us</a></li>
                        <li><a onclick="return confirm('Do you wish to exit ?')"  href="admin.php?page=myplug%2Fmuyplg.php">Exit</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
            <?php
        }

		public function msg(){
			
			if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
				echo "<div class='updated' id='message'><p><strong>".$_SESSION['msg']."</strong></p></div>";
				unset($_SESSION['msg']);
			}
		}
	}
?>