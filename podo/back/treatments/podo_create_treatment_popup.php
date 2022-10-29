<div class="mainClassDokument">
	<h1>Add Treatment </h1><hr>
	<form method="post" action="" id="podo_treatments" name="podo_treatments" enctype="multipart/form-data" class="podo_treatments">

		<label> Name:
			<input type="text" name="treatment_name" id="treatment_name" placeholder="Enter treatment name" required/>
		</label>

		<label> Price:
			<input type="number" name="treatment_price" id="treatment_price" placeholder="Enter treatment price" required/>
		</label>

		<label> Duration:
			<input type="text" name="treatment_duration" id="treatment_duration" placeholder="Enter treatment duration" required/>
		</label>

		<label> Optional description:
			<textarea name="treatment_description" id="treatment_description" rows="5"placeholder="Enter treatment description" style="width:100%;"></textarea>
		</label>

	
		<input type="submit" class="button button-primary" value="Create" id="create_all_treatments"/>

	</form>

</div>