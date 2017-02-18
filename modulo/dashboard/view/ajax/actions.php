<?php
$actions = $this->get("actions");
if($actions):
	foreach($actions as $a):?>
	<option value="<?php echo $a->getId();?>"><?php echo ucfirst($a->getNome());?></option>
	<?php endforeach;
else: ?>
<option value="">Selecione ...</option>
<?php endif;?>
