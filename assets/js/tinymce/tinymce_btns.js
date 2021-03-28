(function(){
	tinymce.create('tinymce.plugins.collapse', {
		init : function(ed, url){
			ed.addButton('collapse', {
				title : '折叠区块',
				image : url+'/collapse.svg',
				onclick : function(){
					ed.selection.setContent('[collapse title="折叠区块标题"]' + ed.selection.getContent() + '[/collapse]');
				}
			});
		},
		createControl:function(n, cm){
			return null;
		},
	});
	tinymce.PluginManager.add('collapse', tinymce.plugins.collapse);
})();