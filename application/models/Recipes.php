<?php
class Application_Model_Recipes
{
	const ERR_NOT_FOUND = 'Ingredient not found in the fridge';
	const ERR_UNIT = "Unit doesn't match";
	const ERR_QUANTITY = "Insufficient quantity";
	const ERR_EXPIRED = 'Ingredient expired';
	
	private static $possible_recipes = array();
	private static $not_possible_recipes = array();
	
	
	/**
	* Calculate the array of possible_recipes and not_possible_recipes
	*
	* @param array $recipes
	* @param array $fridge_ingredients
	*/
	public static function verify_recipes($recipes, $fridge_ingredients)
	{
		foreach ($recipes as $recipe)
		{
			$is_valid_recipe = true;
			$use_by = null;
		
			foreach ($recipe['ingredients'] as $key_ingredient => $ingredient)
			{
				// Matching ingredient of a recipe with an ingredient in the fridge
				if(!array_key_exists($ingredient['item'], $fridge_ingredients))
				{
					$is_valid_recipe = false;
					$recipe['ingredients'][$key_ingredient]['error'][] = self::ERR_NOT_FOUND;
					break;
				}
		
				// Matching of the unit
				if($ingredient['unit'] != $fridge_ingredients[$ingredient['item']]['unit'])
				{
					$is_valid_recipe = false;
					$recipe['ingredients'][$key_ingredient]['error'][] = self::ERR_UNIT;
					break;
				}
		
				// Amount of the ingredient in the fridge must be greater than the one required in the recipe
				if($ingredient['amount'] > $fridge_ingredients[$ingredient['item']]['amount'])
				{
					$is_valid_recipe = false;
					$recipe['ingredients'][$key_ingredient]['error'][] = self::ERR_QUANTITY;
					break;
				}
				
				// Check if ingredient in the fridge is expired
				if(strtotime($fridge_ingredients[$ingredient['item']]['use-by']) < strtotime(date('Y-m-d')))
				{
					$is_valid_recipe = false;
					$recipe['ingredients'][$key_ingredient]['error'][] = self::ERR_EXPIRED;
					break;
				}
		
				// Save the use_by date if is smaller than the previous ingredients
				if(is_null($use_by) || strtotime($fridge_ingredients[$ingredient['item']]['use-by']) < strtotime($use_by))
				{
					$use_by = $fridge_ingredients[$ingredient['item']]['use-by'];
				}
			}
		
			if($is_valid_recipe)
			{
				$recipe['use-by'] = $use_by;
				// Check to decide to insert the recipe in the beginning or in the end of the array depending on the expiration date of the ingredients
				if(!empty(self::$possible_recipes) && (strtotime(self::$possible_recipes[0]['use-by']) < strtotime($use_by)))
				{
					self::$possible_recipes[] = $recipe;
				}
				else
				{
					array_unshift(self::$possible_recipes, $recipe);
				}
		
			}
			else
			{
				self::$not_possible_recipes[] = $recipe;
			}
		}
		
		return self::$possible_recipes;
	}
	
	public static function get_possible_recipe()
	{
		return self::$possible_recipes;
	}
	
	public static function get_not_possible_recipe()
	{
		return self::$not_possible_recipes;
	}
}