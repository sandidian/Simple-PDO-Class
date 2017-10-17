
<?php  

	include 'query.php';

	$query = new Query;

	// $posts = $query->find("posts")->all();
	// $categorys = $query->command("SELECT * FROM categorys")->one();

	$posts = $query
	->find("posts")
	->select("posts .*, category_name")
	->innerJoin([
		'categorys'=>'categorys.id=posts.post_category'
	])
	->all();
	

		
?>

<table cellpadding="5" border="1" style="border-collapse: collapse;">
	<thead>
		<tr>
			<th>No</th>
			<th>Judul</th>
			<th>Kategori</th>
		</tr>
	</thead>
	<tbody>
		<?php  
			$no = 1;
			foreach ($posts as $post) {
				
		?>
				<tr>
					<td><?php echo $no ?></td>
					<td><?php echo $post->post_title ?></td>
					<td><?php echo $post->category_name ?></td>
				</tr>
		<?php  
			}
		?>
	</tbody>
</table>