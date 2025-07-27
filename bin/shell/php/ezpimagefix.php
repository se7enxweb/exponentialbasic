<?php

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$Images = new eZImage();

$allImages = $Images->getAll();

echo "<pre>";
//$i=0;
foreach ($allImages as $image)
	{
		if (!$image->name())
		{
	//		$originalName = $image->originalFileName();
	//		$image->setName($originalName);
	//		$image->store();
			echo "Image name: ".$image->originalFileName()."\n";
			
		}
/*	    $objectPermission = new eZObjectPermission();

        eZObjectPermission::removePermissions( $image->id(), "imagecatalogue_image", "r" );
        eZObjectPermission::removePermissions( $image->id(), "imagecatalogue_image", "w" );
						
		eZObjectPermission::setPermission( -1, $image->id(), "imagecatalogue_image", "r" );
		eZObjectPermission::setPermission( 1, $image->id(), "imagecatalogue_image", "w" );
						
    	$readGroupArrayID =& $objectPermission->getGroups( $image->id(), "imagecatalogue_image", "r", false );
    	$writeGroupArrayID =& $objectPermission->getGroups( $image->id(), "imagecatalogue_image", "w", false );
		
		echo "Read permissions: ";
		print_r($readGroupArrayID);
		echo "\n";
		echo "Write permissions: ";
		print_r($writeGroupArrayID);
		echo "\n\n";
//		$i++;
//		if ($i==500)
//			exit();
*/	
	}


echo "</pre>";

?>