<?

function detailURL($id,$latitude,$longitude) {
  return "/map/detail.php?loc=".$id."&lat=".$latitude."&long=".$longitude."&maptype=roadmap";
}

?>