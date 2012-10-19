<?php
// For AWS PHP SDK
require_once 'AWSSDKforPHP/sdk.class.php';
/* Get data from HTTP POST
$ami = 'ami-d8699bb1';
$instancetype = 't1.micro';
$keyname = $_POST['key'];
$securitygroup = 'default';
 * 
 */
 
//Create the AmazonEC2 object so we can call various APIs.
$ec2 = new AmazonEC2();
$ec2->set_hostname('ec2.us-east-1.amazonaws.com');


$response = $ec2->describe_instances(); 
/*%******************************************************************************************%*/

$instances = array();

echo "<table align='left'>";
echo "<tr> <td> My Instances </td> </tr>";
echo "<r> <td><b>InstanceId</b></td><td><b>InstanceState</b></td><td><b>InstanceType</b></td><td><b>InstanceTime</b></td><td><b>AvailabilityZone</b></td>";
foreach ($response->body->reservationSet->item as $item)
{
$instanceId = (string) $item->instancesSet->item->instanceId;
$instanceState = (string) $item->instancesSet->item->instanceState->name;
$instanceType = (string) $item->instancesSet->item->instanceType;
$instanceTime = (string) $item->instancesSet->item->launchTime;
$instanceLoc = (string) $item->instancesSet->item->placement->availabilityZone;

echo '<tr> <td> ' . $instanceId . ' </td> <td>' . $instanceState . '</td> <td>' . $instanceType . '</td> <td>' . $instanceTime . ' </td> <td>' . $instanceLoc . '</td> </tr>';
}
echo "<p><a href='home.php'>Home</a>";
?>