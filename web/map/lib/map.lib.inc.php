<?php

/**
 * Copyright (c) 2010 West Virginia University
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 */

function detailURL($id,$latitude,$longitude) {
  return "/map/detail.php?loc=".$id."&lat=".$latitude."&long=".$longitude."&maptype=roadmap";
}

?>