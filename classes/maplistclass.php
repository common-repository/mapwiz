<?php

	class maplistClass
	{
		public $pluginDirURL='';
		public function html($row){?>
            <div class="wrapper">
              <div class="top-bar">
                <div class="left">
                  <ul>
                    <li> <a href="#"> <img src="<?=$this->pluginDirURL?>images/logo.png" alt="" /> </a> </li>
                    <li class="go-prem"> <a href="#"> Go Premium </a> </li>
                  </ul>
                </div>
                <div class="right">
                  <ul>
                    <li> <a href="http://mapwiz.io" target="_blank"> About Mapwiz </a> </li>
                    <li> <a href="http://mapwiz.io/#contactform" target="_blank"> Report Bug </a> </li>
                    <li> <a href="http://mapwiz.io/faq/Helping-guide/" target="_blank"> Help Guide </a> </li>
                    <li> <a href="http://mapwiz.io/#contactform" target="_blank"> Contact US </a> </li>
                    <li class="version"> <span> Version 1.0.1 </span> </li>
                  </ul>
                </div>
              </div>
              <!-- top bar -->
              <div class="dashboard-content">
                <div class="left-content">
                  <div class="btn-holder"> <a href="http://mapwiz.io/faq/Helping-guide/" target="_blank" class="btn btn-default" > Help Guide </a> <a href="admin.php?page=myplug/muyplg.php&mid" class="btn btn-default" > Create Map </a> </div>
                  <!-- btn-holder -->
                  
                  <?php
                    if (count($row)){
                        ?>
                  <div class="map-content map-content">
                    <div class="table-holder">
                      <table>
                        <thead>
                          <tr>
                            <th>maps</th>
                            <th>Shortcode</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        $selected = 0;
                        if (isset($_REQUEST["id"])){
                            $selected = intval($_REQUEST["id"]);
                        }
                        $count = 0;
                        $id = $row->id;?>
                          <tr>
                            <td>Map <?=$id?></td>
                            <td><strong class="code">[mapwiz id=<?=$id?>]</strong></td>
                            <td><ul class="action">
                                <li class="del"> <a onclick="return confirm('Are you sure?')" href="admin.php?page=myplug/muyplg.php&mapid=<?=$id;?>"></a> </li>
                                <li class="edit"> <a href="admin.php?page=myplug/muyplg.php&mid=<?=$id;?>"></a> </li>
                              </ul></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <?php }else{?>
                  <div class="map-content create-map">
                    <h2> Please <span><a href="admin.php?page=myplug/muyplg.php&mid">Create</a></span> Your First Map </h2>
                  </div>
                  <?php }?>
                  <div class="bottom-btn premium">
                    <div class="action-btns"> <a href="admin.php?page=myplug/muyplg.php&mid"> New Map </a> </div>
                  </div>
                </div>
                <!-- left Content -->
                <div class="right-content">
                  <div class="plugin"> <strong>Mapwiz</strong>
                    <h2> PREMIUM </h2>
                    <span> Plug-in </span> <a href="http://mapwiz.io" target="_blank"> BUY </a> </div>
                </div>
                <!-- right Content --> 
              </div>
              <!-- dashboard-Content --> 
            </div>
			<?php
		}
	}
?>