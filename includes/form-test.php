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
<h2><a role="button" data-toggle="collapse" href="#sqls-form" aria-expanded="true" aria-controls="sqls-form">SQL Search</a></h2>
<div class="form sqls-form collapse show">
<form id="sqls-form">
<input type="hidden" name="searchtool" value="SQL">
<input type="hidden" name="TaskName" value="Skyserver.Search.SQL">
<input type="hidden" name="syntax" value="Syntax">
<input type="hidden" name="ReturnHtml" value="true">
<input type="hidden" name="format" value="html">
<input type="hidden" name="TableName" value="">
<textarea id="sqls-query" name="cmd" class="sqls-query" rows=10 cols=60></textarea><br>
<button id="sqls-submit" name="sqls-submit" data-sqls-submitto="http://skyserver.sdss.org/dr14/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&syntax=NoSyntax&cmd=" class="sqls-submit btn btn-success">Submit</button>
<button id="sqls-syntax" name="sqls-syntax" data-sqls-submitto="http://skyserver.sdss.org/dr14/en/tools/search/x_results.aspx?searchtool=SQL&TaskName=Skyserver.Search.SQL&ReturnHtml=true&format=html&syntax=Syntax&cmd=" class="sqls-syntax btn btn-warning">Check Syntax</button>
<button id="sqls-reset" name="sqls-reset" class="sqls-reset btn btn-danger">Reset</button>
</form>
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