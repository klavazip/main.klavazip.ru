$(document).ready(function(){
   /// __BXmaker
   var boxs = $('.bxmaker-catalog-sorting');
   for(i=0;i<boxs.length;i++)
   {
        var b = boxs.eq(i);
        // количество отображаемых товаров на странице
        if(b.attr('data-type') == 'pe-count')
        {
            PECount(b);
        }
        // поля сортировки товаров на странице
        else if(b.attr('data-type') == 'pe-sorting')
        {
            PESorting(b);
        }
   }
   
   
   function PECount(b)
   {
        var box = b;
        var label = box.find('.fake-select').eq(0);
        var pop = box.find('.fake-select-popup').eq(0);
       
       label.click(function(){
            pop.filter(':hidden').show();
            pop.filter(':visible').hide();
       });
       pop.click(function(event){
            if(event.target.tagName == 'SPAN')
            {
                pop.hide();
                var b = $(event.target);
                $.cookie(box.attr('data-cookie-uniq'),b.attr('data-val'),{path:'/'});
                label.html(b.attr('data-val'));
                location.reload();
            }
       });
   }
   
   // __ Сортировка товаров по цене, названию и тп
   function PESorting(b) 
   {
        var box = b;
        var label = box.find('.fake-select').eq(0);
        var pop = box.find('.fake-select-popup').eq(0);
       
       label.click(function(){
            pop.filter(':hidden').show();
            pop.filter(':visible').hide();
       });
       pop.click(function(event){
            if(event.target.tagName == 'SPAN')
            {
                pop.hide();
                var b = $(event.target);
                $.cookie(box.attr('data-cookie-uniq'),b.attr('data-sort') + '::' + b.attr('data-val'),{path:'/'});
                label.html(b.html());
                location.reload();
            }
       });
   }
  
   
   
   
      
  
});