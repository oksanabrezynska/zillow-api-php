<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<title>Zillow API- Search</title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</head>

	<body>
		<div class="container" style="margin-top: 60px;">
		<?php 
			extract($_POST);
			if(isset($address)){
				?>
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-default">
					<?php 
						$zwsid = "X1-ZWz1gi4xfl3097_2ninr"; //change with your zw-sid
						$prefixurl = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=";
						$address = urlencode(trim($address));
						$city = urlencode(trim($city));
						$state = urlencode(trim($state));
						$citystatezip = $city."%2C+".$state;
						$query = $prefixurl.$zwsid."&address=".$address."&citystatezip=".$citystatezip."&rentzestimate=true";
						
						$data = file_get_contents($query);
						$xmlString = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $data);
						$xml = SimpleXML_Load_String($xmlString);	
						$message = $xml->message;	
						
						echo '<div class="panel-heading"><h3>'.$message->text.'</h3></div>';
						echo '<div class="panel-body">';
						if($message->code=='0'){
							$response = $xml->response;
							$results = $response->results;
							$result = $results->result;
							if($result){
								echo '<p><b>Property ID:</b> '.$result->zpid.'</p>';
								$links = $result->links;
								echo '<p><b>Property Details:</b> '.$links->homedetails.'</p>';
								echo '<p><b>Tax Year:</b> '.$result->taxAssessmentYear.'</p>';
								echo '<p><b>Tax Amount:</b> '.$result->taxAssessment.'</p>';
								echo '<p><b>Year Built:</b> '.$result->yearBuilt.'</p>';
								echo '<p><b>Lot Size (sqr ft):</b> '.$result->lotSizeSqFt.'</p>';
								echo '<p><b>Finished (sqr ft):</b> '.$result->finishedSqFt.'</p>';
								echo '<p><b>Bathrooms:</b> '.$result->bathrooms.'</p>';
								echo '<p><b>Bedrooms:</b> '.$result->bedrooms.'</p>';
								$zestimate = $result->zestimate;
								echo '<p><b>zestimate amount:</b> '.$zestimate->amount.'</p>';
								$rentzestimate = $result->rentzestimate;
								echo '<p><b>rentzestimate amount:</b> '.$rentzestimate->amount.'</p>';
								
								$zurl = "http://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm?zws-id=".$zwsid."&zpid=".$result->zpid."";
								
								echo '<p><a href="'.$zurl.'" target="_blank">See more details</a></p>';
							}
							
						}
					  echo '</div><!--panel-body-->';
					?>
				</div>
			 </div>
			<?php 		
			}
			?>

			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
				  <div class="panel-heading"><h3>Search in Zillow Properties</h3></div>
				  <div class="panel-body">
					<form method="post" action="">
					  <div class="col-md-6">
						  <div class="form-group">
							<label>Address</label>
							<input type="text" name="address" required class="form-control" placeholder="Enter Address">
							<small class="form-text text-muted">13651 Fieldcrest Rd</small>
						  </div>
					  </div>
					  <div class="col-md-6">
						  <div class="form-group">
							<label>City</label>
							<input type="text" name="city" required class="form-control" placeholder="City">
							<small class="form-text text-muted">Frisco</small>
						  </div>
					  </div>
					  <div class="col-md-6">
						  <div class="form-group">
							<label>State</label>
							<input type="text" name="state" required class="form-control" placeholder="State">
							<small class="form-text text-muted">TX</small>
						  </div>
					  </div>
					  <div class="col-md-6">
						  <div class="form-group">
							<label>Zip Code</label>
							<input type="text" name="zipcode" required class="form-control" placeholder="Zip Code">
							<small class="form-text text-muted">75035</small>
						  </div>
					  </div>
					  <button type="submit" class="btn btn-primary">Submit</button>
					</form>
				  
				  </div>
				</div>
			</div>
		</div>	
		
		<style>
			.form-control {
				border-radius:0px;
			}
			h3{
				margin:0px;
			}
			small { margin-left:10px; }
		</style>
		
	</body>
</html>
