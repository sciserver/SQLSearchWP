<?php 
$result .= <<<EOT
<div class="row">
	<div class="col-lg-12">
		<div class="sqls-messages-wrap">
			<div class="sqls-messages"></div>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="sqls-instructions-wrap well well-sm"> 
			<h2><a role="button" data-toggle="collapse" href="#sqls-instructions" aria-expanded="true" aria-controls="sqls-instructions">Instructions</a></h2>
			<div id="sqls-instructions" class="sqls-instructions collapse"></div> 
		</div>
		<div id="sqls-form-wrap" class="sqls-form-wrap well well-sm"> 
			<h2><a role="button" data-toggle="collapse" href="#sqls-formwrapper" aria-expanded="true" aria-controls="sqls-formwrapper">SQL Search</a></h2>
				<div  id="sqls-formwrapper" class="form sqls-form collapse">
					<form id="sqls-searchform">
						<div class="row">
							<div class="col-xs-12 col-lg-6">
								<div class="showme">
									<div class="form-group">
										<label for="showme">Show me: </label>
										<select name="showme" id="sqls-showme">
											<option name="anyobjects" value="photoobj p" selected="selected">any objects</option>
											<option name="star" value="star p" >stars</option>
											<option name="galaxy" value="galaxy p" >galaxies</option>
											<option name="quasars" value="quasar" >quasars</option>
										</select>
									</div>
								</div>
								<div class="intheregion">
									<div class="form-group">
										<label for="inregion"> in the region </label>
										<select name="inregion" id="sqls-inregion" >
											<option name="anywhere" value="anywhere" selected="selected">anywhere</option>
											<option name="around" value="around" >around</option>
										</select>
									</div>
									<div id="sqls-fpwrapper" aria-expanded="false" class="collapse in">
										<div class="form-group">
											<label for="ra">ra: </label>
											<input type="textbox" name="ra" id="sqls-ra" min="0" max="360" value="258">
										</div>
										<div class="form-group">
											<label for="dec">dec: </label>
											<input type="textbox" name="dec" id="sqls-dec" min="-90" max="90" value="64">
										</div>
										<div class="form-group">
											<label for="radius">radius: </label>
											<input type="textbox" name="radius" id="sqls-radius"  min="0" max="600" value="3">
										</div>
										<div class="form-group">
											<label for="footprint">Check overlap with SDSS footprint:</label>
											<input type="button" name="checkfootprint" id="sqls-fpcheck" 	
												data-sqls-submitto="http://skyserver.sdss.org/public/en/tools/search/x_results.aspx?searchtool=Radial&TaskName=Skyserver.Search.Radial&format=html&topnum=10&fp=only" 
												value="Check Footprint">
										</div>
										<div id="sqls-fpresult">.</div>
									</div>
								</div>
								<div class="withmags">
									<div class="form-group">
										<label>With magnitudes: </label><br>
										<input type="text" name="umin" data-sqls-compare="p.u" id="sqls-umin" min="0" max="30"><span class="ugrizrange">< u <</span><input name="umax" data-sqls-compare="p.u" id="sqls-umax" min="0" max="30"><br>
										<input type="text" name="gmin" data-sqls-compare="p.g" id="sqls-gmin" min="0" max="30"><span class="ugrizrange">< g <</span><input type="text" data-sqls-compare="p.g" name="gmax" id="sqls-gmax" min="0" max="30"><br>
										<input type="text" name="rmin" data-sqls-compare="p.r" id="sqls-rmin" min="0" max="30"><span class="ugrizrange">< r <</span><input type="text" data-sqls-compare="p.r" name="rmax" id="sqls-rmax" min="0" max="30"><br>
										<input type="text" name="imin" data-sqls-compare="p.i" id="sqls-imin" min="0" max="30"><span class="ugrizrange">< i <</span><input type="text" data-sqls-compare="p.i" name="imax" id="sqls-imax" min="0" max="30"><br>
										<input type="text" name="zmin" data-sqls-compare="p.z" id="sqls-zmin" min="0" max="30"><span class="ugrizrange">< z <</span><input type="text" data-sqls-compare="p.z" name="zmax" id="sqls-zmax" min="0" max="30"><br>
									</div>
								</div>
								<div class="andcolors">
									<div class="form-group">
										<label>And colors: </label><br>
										<input type="text" name="ugmin" data-sqls-compare="p.u-p.g" id="sqls-ugmin" min="-20" max="20"><span class="ugrizrange">< u-g <</span><input type="text" name="ugmax" data-sqls-compare="p.u-p.g" data-sqls-compare="p.u-p.g" id="sqls-ugmax" min="-20" max="20"><br>
										<input type="text" name="grmin" data-sqls-compare="" data-sqls-compare="p.g-p.r" id="sqls-grmin" min="-20" max="20"><span class="ugrizrange">< g-r <</span><input type="text" name="grmax" data-sqls-compare="p.g-p.r" id="sqls-grmax" min="-20" max="20"><br>
										<input type="text" name="rimin" data-sqls-compare="" data-sqls-compare="p.r-p.i" id="sqls-rimin" min="-20" max="20"><span class="ugrizrange">< r-i <</span><input type="text" name="rimax" data-sqls-compare="p.r-p.i" id="sqls-rimax" min="-20" max="20"><br>
										<input type="text" name="izmin" data-sqls-compare="" data-sqls-compare="p.i-p.z" id="sqls-izmin" min="-20" max="20"><span class="ugrizrange">< i-z <</span><input type="text" name="izmax" data-sqls-compare="p.i-p.z" id="sqls-izmax" min="-20" max="20"><br>
										<input type="text" name="urmin" data-sqls-compare="p.u-p.r" id="sqls-urmin" min="-20" max="20"><span class="ugrizrange">< u-r <</span><input type="text" name="urmax" data-sqls-compare="p.u-p.r" id="sqls-urmax" min="-20" max="20"><br>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-lg-6">
								<div class="forobjects">
									<div class="form-group">
										<label for="forobjects">For: </label>
										<select name="forobjects" id="sqls-forobjects">
											<option name="allobjects" value="all objects" selected="selected">all objects</option>
											<option name="onlyobjects" value="only objects" >only objects with spectra</option>
										</select>
									</div>
								</div>
								<div id="sqls-forwrapper" aria-expanded="false" class="collapse in">
									<div class="form-group">
										<label for="redshiftmin">With redshifts between </label>
										<input type="textbox" name="redshiftmin" id="sqls-redshiftmin" min="0" max="6">
										<label for="redshiftmax"> and </label>
										<input type="textbox" name="redshiftmax" id="sqls-redshiftmax" min="0" max="6">
									</div>
								</div>
								<div class="pleasereturn">
									<label>Return: </label><br>
									<div class="returnobj">
										<div class="form-group">
											<label for="numobj">Number of objects: </label><br>
											<input type="radio" name="numobj" value="count" id="numobjcount"> Count Only<br>
											<input type="radio" name="numobj" value="10" id="numobj10" checked> 10<br>
											<input type="radio" name="numobj" value="100" id="numobj100"> 100<br>
											<input type="radio" name="numobj" value="1000" id="numobj1000"> 1000<br>
											<input type="radio" name="numobj" value="all" id="numobjall"> All
										</div>
									</div>
									<div class="returnimg">
										<div class="form-group">
											<label>Image Data: </label><br>
											<input type="checkbox" name="imgdataobjids" value="p.objid" id="imgdataobjids" checked> Object IDs<br>
											<input type="checkbox" name="imgdataradec" value="p.ra, p.dec" id="imgdataradec" checked> RA and Dec<br>
											<input type="checkbox" name="imgdatamags" value="p.u, p.g, p.r, p.i, p.z" id="imgdatamags" > Magnitudes<br>
										</div>
									</div>
									<div id="sqls-returnspectra" aria-expanded="false" class="returnspectra collapse in">
										<div class="form-group">
											<label>Spectral Data: </label><br>
											<input type="checkbox" name="specdataredshift" value="s.z as redshift" id="specdataredshift"> redshift<br>
											<input type="checkbox" name="specdataspectrumid" value="s.specobjid" id="specdataspectrumid"> spectrum ID<br>
											<input type="checkbox" name="specdataplate" value="s.plate, s.mjd, s.fiberid" id="specdataplate"> plate | mjd | fiber<br>
										</div>
									</div>
								</div>
								<div class="sqlgeneratequery">
									<button name="generate" id="sqls-generate" data-sqls-generatetoid="#sqls-query" class="btn btn-success">Generate Query</button>
									<!-- button name="edit" id="sqls-editquery" class="btn btn-info">Edit Query</button -->
									<!-- button name="check" id="sqls-checkquery" class="btn btn-primary">Check Query</button -->
								</div>
							</div>
							<div class="col-xs-12">
								<div class="alert alert-success" role="alert">Remember that the magnitude scale is backward! For objects brighter than 18 in g, use g < 18.</div>
							</div>
						</div>						
					</form>
					<form id="sqls-form">
						<div class="row">
							<div class="col-xs-12 col-xs-offset-3">
								<input type="hidden" name="searchtool" value="SQL">
								<input type="hidden" name="TaskName" value="Skyserver.Search.SQL">
								<input type="hidden" name="syntax" value="Syntax">
								<input type="hidden" name="ReturnHtml" value="true">
								<input type="hidden" name="format" value="html">
								<input type="hidden" name="TableName" value="">
								<textarea id="sqls-query" name="cmd" class="sqls-query" rows=10 cols=60></textarea><br>
								<button id="sqls-submit" name="sqls-submit" data-sqls-submitto="http://skyserver.sdss.org/public/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&syntax=NoSyntax&cmd=" class="sqls-submit btn btn-success">Submit to SkyServer</button>
								<button id="sqls-images" name="sqls-images" data-sqls-submitto="http://skyserver.sdss.org/public/en/tools/chart/f_sql.aspx?cmd=" class="sqls-images btn btn-success">Submit to Image List</button>
								<button id="sqls-syntax" name="sqls-syntax" data-sqls-submitto="http://skyserver.sdss.org/public/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&syntax=Syntax&cmd=" class="sqls-syntax btn btn-warning">Check Syntax</button>
								<button id="sqls-reset" name="sqls-reset" class="sqls-reset btn btn-danger">Reset</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="sqls-results-wrap well well-sm"> 
			<h2><a role="button" data-toggle="collapse" href="#sqls-results" aria-expanded="false" aria-controls="sqls-results">Results</a></h2>
			<div id="sqls-results" class="sqls-results collapse"></div>
		</div>
	</div>
</div>
EOT;
?>