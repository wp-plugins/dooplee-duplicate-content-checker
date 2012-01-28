<?php
/*
Plugin Name: Dooplee Duplicate Content Checker
Plugin URI: http://dooplee.com/
Description: This plugin loads in the admin panel and lets you check for duplicate content
Version: 1.0
Author: Jennifer Marsh
Author URI: http://jennifer-marsh.com
License: GPL
*/


if ( is_admin() ){

	add_action('admin_menu', 'dooplee_menu');

	function dooplee_menu() {
		add_options_page('Dooplee Duplicate Content Checker', 'Dooplee Duplicate Content Checker', 'administrator',
		'dooplee-checker', 'dooplee_page');
	}
	
	
	function dooplee_page() {
	
	//echo "<link rel=\"stylesheet\" href=\"". plugins_url( 'search.css', __FILE__ ) ."\" type=\"text/css\" media=\"screen\" />";	
	echo "<link rel=\"stylesheet\" href=\"". plugins_url( 'styles.css', __FILE__ ) ."\" type=\"text/css\" media=\"screen\" />";	
	//echo "<script type=\"text/javascript\" src=\"". plugins_url( 'function.js' , __FILE__ ) ."\"></script>";

	
?>

<h2>Dooplee Duplicate Content Checker</h2>
<div  id="main">
	<p>
		Duplee content checker searches Google for any websites hosting your content, so you can check for scrapers
		and auto-bloggers that steal your content. The last 10 posts are shown below, but you can also copy and paste sentences from other
		posts and paste them into the search text box. We suggest copying a sentence from your posts
		and placing a sentence in the search. </p>
	<p>
		Dooplee offers a DMCA service you use to have duplicate content removed from the server and
		search engines. Even if the content cannot be taken down from the host server, Dooplee
		can have it removed from the top search engines.</p>
	<p>	
		If you find duplicate content, click the link next to WordPress post title to submit a DMCA report
		to Dooplee. We will evaluate the issue and return a response within 48 hours. For more information,
		see <a href="http://dooplee.com">Dooplee</a> or our <a href="http://dooplee.com/blog">blog</a>.
	</p>
	
	<h3>How to use this plugin</h3>
	<p>
		The last 10 posts are shown below. Click "Check for Duplicates" to fill the search text box and click
		the "Search" button to perform the search. If you find any stolen content, click the 
		"Report" button under the title that has been copied. The button scrolls down to the bottom of the page to the DMCA evaluation
		form. Fill out the rest of the form and click "Submit" to send 
		Dooplee a DMCA complaint. 
	</p>
	<div id="error_messages"> 
		<?php
			include(ABSPATH . 'wp-content/plugins/dooplee-duplicate-content-checker/contact.php');
			//print errors from form submission
			if($_SERVER['REQUEST_METHOD'] == "POST") 
			{
				submitForm();
			}
		
		?>
	</div>
	
	<div id="articlelist"> <!-- start article list div -->
		<h3> Recent Posts </h3>
		
			<ul>
			<?php
				// the_permalink();
				global $post;
				$args = array( 'numberposts' => 10, 'post_status'=>'publish', 'orderby' => 'date', 'order' => 'DESC');
				$dup_posts = get_posts( $args );
				foreach( $dup_posts as $post ) :	
					setup_postdata($post); 
					$description = substr(get_the_content(), 0, 150); ?>
					<div id="title_<?php the_ID(); ?>"> <!-- start title list div -->
					<li>
						<a href="<?php the_permalink(); ?>" id="link_<?php the_ID(); ?>" ><?php the_title(); ?></a> <br>
						<input type="button" id="post_<?php the_ID(); ?>" value="Check for Duplicates" onclick="checkDups(this);">
						<input type="button" id="report_<?php the_ID(); ?>" value="Report" onclick="filloutForm(this);">
						<input type="hidden" id="content_<?php the_ID(); ?>" value="<?php  echo $description; ?>" ><br>
					</li>
					</div> <!-- end title list div -->
				<?php endforeach; ?>
				
			</ul>
		
	
	</div> <!-- end article list div -->
	
	
	<div id="searchdiv"> <!-- search div -->
		<div id="cse">Loading</div>
		<script src="//www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript"> 
		  google.load('search', '1', {language : 'en'});
		  google.setOnLoadCallback(function() {
			var customSearchOptions = {};
		  
			var imageSearchOptions = {};
			imageSearchOptions['layout'] = google.search.ImageSearch.LAYOUT_POPUP;
			customSearchOptions['enableImageSearch'] = true;
			customSearchOptions['imageSearchOptions'] = imageSearchOptions;
		  
			var customSearchControl = new google.search.CustomSearchControl(
			  '013262094359944818147:iwoktqts_l0', customSearchOptions);

			customSearchControl.setResultSetSize(google.search.Search.LARGE_RESULTSET);
			customSearchControl.draw('cse');
		  }, true);
		</script>
	
	</div> <!-- end search div -->
	
	<div id="report">
		<h3>Dooplee form - File a DMCA to Remove Content Theft</h3>
		<form name="report_submit" method="post">
			Your Name: <input type="text" name="name" id="name" size="25px"><br>
			Infringing URL: <input type="text" name="infringingurl" id="infringingurl" size="100px"><br>
			Your URL (original URL): <input type="text" name="originalurl" id="originalurl" size="100px"><br>
			Your Email: <input type="text" id="email" name="email" size="25px"><br>
			Describe the Infringement:<br>
			<textarea cols="60" rows="10" id="comments" name="comments"></textarea>
			<div><input checked="checked" name="who" type="radio" value="Iown" />&nbsp;I own the copyright </div>
            <div><input name="who" type="radio" value="business_owns" />&nbsp;My business owns the copyright </div>
            <div><input name="who" type="radio" value="agent" />&nbsp;I am the business agent authorized to file the notice</div>
			<br><input type="submit" value="Submit to Dooplee" id="submitform" name="submitform">
			<div id="notification"></div>
			
		
		</form>
	</div> 
	
	

</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

var currentDiv  = 0;

function checkDups(post)
{
	var buttonid = post.id;
	var id = buttonid.substring(5);
	var content = document.getElementById('content_'+id);
	var searchString = content.value.substring(0, 200);
	var searchBox = document.getElementsByName('search');
	for(var i=0; i<searchBox.length; i++)
		{
		  var sname = searchBox[i];
		  //alert(sname.type);
		  if (sname.name == 'search')
			{
				sname.value = searchString;
			}
		}
	
	//set background color
	if (currentDiv != 0 )
		{
			var oldDiv = document.getElementById(currentDiv);
			oldDiv.style.backgroundColor = 'transparent';
		}
	var titlediv = document.getElementById('title_'+id);
	titlediv.style.backgroundColor = '#eee';
	titlediv.style.borderRadius  = '10px';
	currentDiv  = titlediv.id;
	
	

}

function filloutForm(post) 
{
	var buttonid = post.id;
	var id = buttonid.substring(7);
	document.getElementById('report_'+id).scrollIntoView(true);
	var link = document.getElementById('link_'+id);
	var location = link.href;
	document.getElementById('originalurl').value = location;
	
}




</script>

<?php

		

	} //end of dooplee_page()

}  //end of is_admin()

?>