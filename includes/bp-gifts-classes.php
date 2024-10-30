<?php



/**

 * This function should include all classes and functions that access the database.

 * In most BuddyPress components the database access classes are treated like a model,

 * where each table has a class that can be used to create an object populated with a row

 * from the corresponding database table.

 * 

 * By doing this you can easily save, update and delete records using the class, you're also

 * abstracting database access.

 */



class BP_Gifts {

	var $id;

	var $gift_name;

	var $gift_image;

	var $category;

	var $point;

	

	/**

	 * bp_gifts_item()

	 *

	 * This is the constructor, it is auto run when the class is instantiated.

	 * It will either create a new empty object if no ID is set, or fill the object

	 * with a row from the table if an ID is provided.

	 */

	function bp_gifts( $id = null ) {

		global $wpdb, $bp;

		

		if ( $id ) {

			$this->id = $id;

			$this->populate( $this->id );

		}

	}

	

	/**

	 * populate()

	 *

	 * This method will populate the object with a row from the database, based on the

	 * ID passed to the constructor.

	 */

	function populate() {

		global $wpdb, $bp, $creds;

		

		if ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$bp->gifts->table_name} WHERE id = %d", $this->id ) ) ) {

			$this->gift_name = $row->gift_name;

			$this->gift_image = $row->gift_image;

			$this->category = $row->category;

			$this->point = $row->point;

		}

	}

	

	/**

	 * save()

	 *

	 * This method will save an object to the database. It will dynamically switch between

	 * INSERT and UPDATE depending on whether or not the object already exists in the database.

	 */

	

	function save() {

		global $wpdb, $bp;

	

		/* Call a before save action here */

		do_action( 'bp_gifts_data_before_save', $this );

						

		if ( $this->id ) {

			// Update

			echo $this->gift_name;

			echo $this->gift_id;

			$result = $wpdb->query( $wpdb->prepare( 

					"UPDATE {$bp->gifts->table_name} SET 

						gift_name = %s,

						gift_image = %s,

						category = %s,

						point = %d

					WHERE id = %d",

						$this->gift_name,

						$this->gift_image,

						$this->category,

						$this->point,

						$this->id 

					) );

		} else {

			// Save

			$result = $wpdb->query( $wpdb->prepare( 

					"INSERT INTO {$bp->gifts->table_name} ( 

						gift_name,

						gift_image,

						category,

						point 

					) VALUES ( 

						%d, %d, %d, %d 

					)", 

						$this->gift_name,

						$this->gift_image,

						$this->category,

						$this->point

					) );

		}

				

		if ( !$result )

			return false;

		

		if ( !$this->id ) {

			$this->id = $wpdb->insert_id;

		}	

		

		/* Add an after save action here */

		do_action( 'bp_gifts_data_after_save', $this ); 

		

		return $result;

	}



	/**

	 * delete()

	 *

	 * This method will delete the corresponding row for an object from the database.

	 */	

	function delete() {

		global $wpdb, $bp;

		

		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->gifts->table_name} WHERE id = %d", $this->id ) );

	}



	/* Static Functions */



	/**

	 * Static functions can be used to bulk delete items in a table, or do something that

	 * doesn't necessarily warrant the instantiation of the class.

	 *

	 * Look at bp-core-classes.php for giftss of mass delete.

	 */



	function delete_all() {



	}



	function delete_by_user_id() {



	}

}



function bp_gifts_allgift() {

	global $bp, $wpdb;

	

	$allgift = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$bp->gifts->table_name} ") );

	return $allgift;



}



function bp_gifts_newgift($giftname, $giftimage, $category = 'gifts', $point = 0) {

	global $bp, $wpdb;

	$insertgift = $wpdb->prepare("INSERT INTO {$bp->gifts->table_name} (gift_name, gift_image, category, point) VALUES (%s, %s, %s, %d)",$giftname, $giftimage, $category, $point);

	$newgift = $wpdb->query( $insertgift );

	return $newgift;



}	

?>