<a href="{:U('index'))}" class="mui-control-item <?php if(!isset($_GET['type'])) echo 'mui-active'; ?>">最新</a>
				<a href="{:U('index',array('order'=>num,'type'=>1)))}" class="mui-control-item <?php if($_GET['type']==1) echo 'mui-active'; ?> ">最热</a>
				<a href="{:U('index',array('type'=>2)))}" class="mui-control-item <?php if($_GET['type']==2) echo 'mui-active'; ?> ">已解决</a>