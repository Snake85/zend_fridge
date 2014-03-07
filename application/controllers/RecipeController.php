<?php

class RecipeController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function resultAction()
    {
        $this->view->page_title = 'Recipe result';
        
        try
        {
        	// Check inputs
        	if (!isset($_FILES['fridge']['tmp_name'])) {
        		throw new Exception('Fridge ingradients file is missing!');
        	}
        	if (pathinfo($_FILES['fridge']['name'], PATHINFO_EXTENSION) != 'csv') {
        		throw new Exception('Fridge ingredients file has a wrong format!');
        	}
        
        	if (!isset($_FILES['recipes']['tmp_name'])) {
        		throw new Exception('Recipes file is missing!');
        	}
        	if (pathinfo($_FILES['recipes']['name'], PATHINFO_EXTENSION) != 'json') {
        		throw new Exception('Recipes file has a wrong format!');
        	}
        
        
        
        	// Convert csv file to array
        	$fridge_ingredients = array();
        	$fridge_file = fopen($_FILES['fridge']['tmp_name'], 'r');
        
        	$unit_types = array('ml', 'grams', 'slices', 'of');
        	while (($result = fgetcsv($fridge_file)) !== false)
        	{
        		if(!isset($result[0]) || !isset($result[1]) || !isset($result[2]) || !isset($result[3])) {
        			continue;
        		}
        
        		
        		// Filter the inputs
        		$item = Zend_Filter::filterStatic($result[0], 'Alnum', array('allowwhitespace' => true));
        	    $amount = (int)$result[1];
        	    
        	    if(!in_array($result[2], $unit_types))
        	    {
        	        continue;
        	    }
        	    
        	    // Convert date format
        	    $use_by_tmp = explode('/', $result[3]);
        	    $use_by = $use_by_tmp[2] . '-' . $use_by_tmp[1] . '-' . $use_by_tmp[0];
        		
        	    // I could use even an object Ingredient
        		$fridge_ingredients[strtolower($result[0])] = array(
        				'item' => $item,
        				'amount' => $amount,
        				'unit' => $result[2],
        				'use-by' => $use_by,
        		);
        	}
        
        	fclose($fridge_file);
        
        	if (empty($fridge_ingredients)) {
        		throw new Exception('Fridge ingredients file is empty!');
        	}
        
        
        
        	// Decode json file to array
        	$string = file_get_contents($_FILES['recipes']['tmp_name']);
        	$recipes = json_decode($string, true);
        	// I could even transform it in objects Recipe
        	
        	if (empty($recipes)) {
        		throw new Exception('Recipes file is empty or not well formatted!');
        	}
        
        
        	
        	Application_Model_Recipes::verify_recipes($recipes, $fridge_ingredients);
        	
        	$this->view->fridge_ingredients = $fridge_ingredients;
        	$this->view->possible_recipes = Application_Model_Recipes::get_possible_recipe();
        	$this->view->not_possible_recipes = Application_Model_Recipes::get_not_possible_recipe();
        }
        catch (Exception $e)
        {
        	die($e->getMessage());
        }
    }


}

