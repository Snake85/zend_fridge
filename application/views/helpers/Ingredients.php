<?php
class Zend_View_Helper_Ingredients extends Zend_View_Helper_Abstract
{
    public function ingredients($fridge_ingredients)
    {
 ?>
        <dl id="fridge_ingredients">
			<?php foreach ($fridge_ingredients as $ingredient): ?>
				<?php $expired = (strtotime($ingredient['use-by']) < strtotime(date('Y-m-d'))) ? true : false; ?>
				<dt class="<?php if($expired){ echo 'expired'; }?>"><?php echo $this->view->escape(ucwords($ingredient['item'])); ?></dt>
				<dd><span class="<?php if($expired){ echo 'expired'; }?>"><?php echo $this->view->escape(date('d/m/Y', strtotime($ingredient['use-by']))); ?></span> - <?php echo $this->view->escape($ingredient['amount']); ?> <?php echo $this->view->escape(ucwords($ingredient['unit'])); ?></dd>
			<?php endforeach; ?>
		</dl>
<?php
    }
}
?>