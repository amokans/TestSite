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
 
// Create the AmazonEC2 object so we can call various APIs.
$ec2 = new AmazonEC2();

// Create a new security group.
$response = $ec2->create_security_group ( 'GettingStartedGroup', 'Getting Started Security Group');
if (!$response->isOK())
{
	if (((string) $response->body->Errors->Error->Code) === 'InvalidGroup.Duplicate')
	{
		// This means that the group is already created, so ignore.
		echo 'create_security_group returned an acceptable error: ' . $response->body->Errors->Error->Message . PHP_EOL;
	} else {
		print_r($response);
		exit();
	}
}

// TODO - Change the code below to use your external ip address. 
$ip_source = '0.0.0.0/0';

// Open up port 22 for TCP traffic to the associated IP
// from above (e.g. ssh traffic).
$ingress_opt = array(
	'GroupName' => 'GettingStartedGroup',
	'IpPermissions' => array(
		array(
			'IpProtocol' => 'tcp',
			'FromPort' => '22',
			'ToPort' => '22',
			'IpRanges' => array(
				array('CidrIp' => $ip_source),
			)
    )
	)
);

// Authorize the ports to be used.
$response = $ec2->authorize_security_group_ingress($ingress_opt);
if (!$response->isOK()) 
{
	if (((string) $response->body->Errors->Error->Code) === 'InvalidPermission.Duplicate') 
	{
		echo 'authorize_security_group_ingress returned an acceptable error: ' .$response->body->Errors->Error->Message .PHP_EOL;
	} else {
		print_r($response);
		exit();
	}
}

// Setup the specifications of the launch. This includes the
// instance type (e.g. t1.micro) and the latest Amazon Linux
// AMI id available. Note, you should always use the latest
// Amazon Linux AMI id or another of your choosing.
$spot_opt = array(
	'InstanceCount' => 1,
	'LaunchSpecification' => array(
		'ImageId' => 'ami-31814f58',
		'SecurityGroup' => 'GettingStartedGroup1',
		'InstanceType' => 't1.micro'
	)
);

// Request 1 x t1.micro instance with a bid price of $0.03.
$response = $ec2->request_spot_instances('0.03', $spot_opt);
if (!$response->isOK()) 
{
	print_r($response);
	exit();
}	

// Request 1 x t1.micro instance with a bid price of $0.03.
$response = $ec2->request_spot_instances('0.03', $spot_opt);
if (!$response->isOK()) 
{
	print_r($response);
	exit();
}

$spot_instance_request_ids = array();
for ($i=0; $i < $response->body->spotInstanceRequestSet->item->count(); $i++) 
{
	$spot_instance_request_id = (string)$response->body->spotInstanceRequestSet->item[$i]->spotInstanceRequestId;
	$spot_instance_request_ids[] = $spot_instance_request_id;
}

// Initialize a variable that will track whether there are any
// requests still in the open state.
$any_open = false;

// Initialize an array to hold any instances we activate so we can terminate them later.
$instance_ids = array();

do {
	// Call describe_spot_instance_requests with all of the request ids to
	// monitor (e.g. that we started).
	$describe_opt = array(
		'SpotInstanceRequestId' => $spot_instance_request_ids
	);
	$response = $ec2->describe_spot_instance_requests($describe_opt);		
	if (!$response->isOK()) 
	{
		print_r($response);
		exit();
	}

	// Reset the any_open variable to false - which assumes there
	// are no requests open unless we find one that is still open.
	$any_open = false;

	// Look through each request and determine if they are all in
	// the active state.
	foreach ($response->body->spotInstanceRequestSet->item as $item) 
	{
		echo "spotInstanceRequestId = $item->spotInstanceRequestId, state = $item->state" . PHP_EOL;
		
		// If the state is open, it hasn't changed since we attempted
		// to request it. There is the potential for it to transition
		// almost immediately to closed or cancelled so we compare
		// against open instead of active.
		if (((string)$item->state) === 'open') 
		{
			$any_open = true;
			break;
		}
		
		if (((string)$item->state) === 'active') 
		{
			// Get the instanceId once the spot instance request is active
			$instance_id = (string)$item->instanceId;
			echo 'Instance'.$instanceId.' is active.' . PHP_EOL;
			
			// Store the instanceId for any instances we've started so we can terminate them later.
			if (!in_array($instanceId, $instanceIds)) 
			{
				$instance_ids[] = (string)$item->instanceId;
			}
		}
	}
	
	if ($any_open) 
	{
		echo 'Requests still in open state, will retry in 60 seconds.' . PHP_EOL;
		sleep(60);
	}
} 
while($any_open);
?>

