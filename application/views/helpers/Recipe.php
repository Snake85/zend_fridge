<?php
class Zend_View_Helper_Recipe extends Zend_View_Helper_Abstract
{
    public function recipe($recipes, $expanded = true)
    {
 ?>
 		<?php if(empty($recipes)): ?>
 			<p>No Recipes</p>
 		<?php else: ?>
			<dl class="recipes">
			<?php foreach ($recipes as $recipe): ?>
	            <dt>- <?php echo $this->view->escape(ucfirst($recipe['name'])); ?></dt>
		        <dd>
		           <table style="width: 75%" class="recipes_ingredients">
	            	<?php
					foreach ($recipe['ingredients'] as $ingredient) :
					?>
					    <tr>
					        <td width="3%">
					            <?php if(isset($ingredient['error'])) :?>
					                <img height="13px" alt="X" src="../static/img/Red_X.png"/>
					            <?php else: ?>
					                <img height="15px" alt="V" src="../static/img/Green_V.png"/>
					            <?php endif; ?>
					        </td>
					        <td width="25%"><?php echo $this->view->escape(ucfirst($ingredient['item'])); ?></td>
					        <td width="5%"><?php echo $this->view->escape($ingredient['amount']); ?></td>
					        <td width="7%"><?php echo $this->view->escape($ingredient['unit']); ?></td>
					        <td width="35%">
					            <?php if(isset($ingredient['error'])) :?>
	        				        <?php foreach ($ingredient['error'] as $error): ?>
	        				        <span style="color: red; font-weight: bold;"><?php echo $error; ?></span>
	        				        <?php endforeach; ?>
					            <?php endif; ?>
					        </td>
					    </tr>
					<?php endforeach; ?>
					</table>
		        </dd>
	    	<?php endforeach; ?>
	    	</dl>
	    <?php endif; ?>
<?php
    }
}
?>