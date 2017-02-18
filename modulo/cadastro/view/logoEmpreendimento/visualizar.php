<?php
$empreendimento = $this->get('empreendimento');
$image = $this->get('image');
if(!file_exists($image)){
    $image = 'public/images/imagemInvalida.jpg';
}
?>
<div id="modal-title" style="display: none"></div>
<div class="modal-body" align="center">
<h4 align="center"><strong><?php echo $empreendimento['nm_empreendimento'] ?></strong></h4>
    <div class="img-thumbnail" align="center"><img align="center" height="80px" style="max-height: 100%; max-width: 100%" src="<?php echo $image ?>"></div>
</div>
