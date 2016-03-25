
(function($){
	$.fn.movebg=function(options){
		var defaults={
		width:120,/*�ƶ���Ĵ�С*/
		extra:50,/*�����ľ���*/
		speed:300,/*���ƶ����ٶ�*/
		rebound_speed:300/*�鷴�����ٶ�*/
		};
	var defaultser=$.extend(defaults,options);
	return this.each(function(){
		var _this=$(this);
		var _item=_this.children("ul").children("li").children("a");/*�ҵ��������黬����Ԫ��	*/
		var origin=_this.children("ul").children("li.selected").index();/*��õ�ǰ����������*/
		var _mover=_this.find(".move-bg");/*�ҵ�����*/
		var hidden;/*����һ��������html��û�й涨curʱ������Ƴ���������ʧ*/
		if (origin==-1){origin=0;hidden="1"} else{_mover.show()};/*���û�ж���cur,��Ĭ�ϴӵ�һ����������*/
		var cur=prev=origin;/*��ʼ����ǰ������ֵ������һ������ʼֵ;*/
		var extra=defaultser.extra;/*����һ��������ʾ���⻬���ľ���*/
		_mover.css({left:""+defaultser.width*origin+"px"});/*���û��鵱ǰ��ʾ��λ��*/
		
		//������꾭���¼�
		_item.each(function(index,it){
			$(it).mouseover(function(){
				cur=index;/*�Ե�ǰ����ֵ���и�ֵ*/
				move();
				prev=cur;/*������ɶ��ϸ�����ֵ���и�ֵ*/
			});
		});
		_this.mouseleave(function(){
			cur=origin;/*����뿪����ʱ��ǰ����ֵ�����������ֵ*/
			move();
			if(hidden==1){_mover.stop().fadeOut();}/*��html��û�й涨curʱ������Ƴ���������ʧ*/
		});
		
		//��������
		function move(){
			_mover.clearQueue();
			if(cur<prev){extra=-Math.abs(defaultser.extra);} /*����ǰֵС���ϸ�����ֵʱ�����⻬��ֵΪ����*/
			else{extra=Math.abs(defaultser.extra)};/*����ǰֵ�����ϸ�����ֵʱ������ֵΪ����*/
			_mover.queue(
				function(){
					$(this).show().stop(true,true).animate({left:""+Number(cur*defaultser.width+extra)+""},defaultser.speed),
					function(){$(this).dequeue()}
				}
			);
			_mover.queue(
				function(){
					$(this).stop(true,true).animate({left:""+cur*defaultser.width+""},defaultser.rebound_speed),
					function(){$(this).dequeue()}
				}
			);
		};
	})
	}
})(jQuery);

