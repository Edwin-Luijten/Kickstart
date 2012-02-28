<div id="setup">
    <h1>Setup</h1>
    <form action="setup/save" method="post">
	<fieldset>
	    <legend>Framework</legend>
	    <div class="clearfix">
		<label for="app_name">Application name</label>
		<div class="input">
		    <input id="app_name" class="xlarge" type="text" size="30" name="app_name" />
		</div>
	    </div>
	    <div class="clearfix">
		<label for="app_url">Application URL</label>
		<div class="input">
		    <input id="app_url" class="xlarge" type="text" size="30" name="app_url" />
		    <span class="help-block">e.g.: www.example.com</span>
		</div>
	    </div>
	    <div class="clearfix">
		<label for="app_modules">Modules</label>
		<div class="input span6">
		    <select id="app_modules" class="medium" name="app_modules[]" multiple="">
			<?php
			    foreach($modules as $module => $name):
			?>
			<option><?=ucfirst($name)?></option>
			<?php endforeach ?>
		    </select>
		    <span class="help-block span12">
			    For windows: Hold down the control (ctrl) button to select multiple options<br/>
			    For Mac: Hold down the command button to select multiple options
		    </span>
		</div>
	    </div>
	</fieldset>
	<fieldset>
	    <legend>Database</legend>
	    <div class="clearfix">
		<label for="db_host">Database Host</label>
		<div class="input">
		    <input id="db_host" class="xlarge" type="text" size="30" name="db_host" />
		    <span class="help-block">e.g.: localhost</span>
		</div>
	    </div>
	    <div class="clearfix">
		<label for="db_name">Databasename</label>
		<div class="input">
		    <input id="db_name" class="xlarge" type="text" size="30" name="db_name" />
		</div>
	    </div>
	    <div class="clearfix">
		<label for="db_username">Database Username</label>
		<div class="input">
		    <input id="db_username" class="xlarge" type="text" size="30" name="db_username" />
		</div>
	    </div>
	    <div class="clearfix">
		<label for="db_password">Database Password</label>
		<div class="input">
		    <input id="db_password" class="xlarge" type="password" size="30" name="db_password" />
		</div>
	    </div>
	    <div class="clearfix">
		<label for="db_type">Database Type</label>
		<div class="input">
		    <select id="db_type" class="medium" name="db_type">
			<option>Mysql</option>
			<option>Sqlite</option>
			<option>Sysbase</option>
			<option>Mssql</option>
		    </select>
		</div>
	    </div>
	    <div class="clearfix">
		<label for="db_file">Database File</label>
		<div class="input">
		    <input id="db_file" class="xlarge" type="text" size="30" name="db_file" />
		    <span class="help-block">e.g.: mydatabase.db</span>
		</div>
	    </div>
	</fieldset>
	<fieldset>
	    <legend>Cache</legend>
	    <div class="clearfix">
		<label id="cache">Cache</label>
		<div class="input span6">
		    <ul class="inputs-list">
			<li>
			    <label>
				<input type="radio" value="false" name="cache" checked="" />
				<span>Dissable the cache module.</span>
			    </label>
			</li>
			<li>
			    <label>
				<input type="radio" value="true" name="cache" />
				<span>Enable the cache module.</span>
			    </label>
			</li>
		    </ul>
		</div>
	    </div>
	    <div class="clearfix">
		<input class="btn primary" type="submit" value="Save configuration" />
		<button class="btn" type="reset">Cancel</button>
	    </div>
	</fieldset>
    </form>
</div>