(function ($) {

   $.fn.pickList = function (options) {

      var opts = $.extend({}, $.fn.pickList.defaults, options);

      this.fill = function () {
         var option = '';

         $.each(opts.data, function (key, val) {
            option += '<option value='+val.id+' data-id=' + val.id + '>' + val.text + '</option>';
         });
         this.find('.pickData').append(option);
      };
      this.controll = function () {
         var pickThis = this;

         pickThis.find(".pAdd").on('click', function () {
            var p = pickThis.find(".pickData option:selected");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pAddAll").on('click', function () {
            var p = pickThis.find(".pickData option");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pRemove").on('click', function () {
            var p = pickThis.find(".pickListResult option:selected");
            p.clone().prependTo(pickThis.find(".pickData"));
            p.remove();
         });

         pickThis.find(".pRemoveAll").on('click', function () {
            var p = pickThis.find(".pickListResult option");
            p.clone().appendTo(pickThis.find(".pickData"));
            p.remove();
         });
      };

      this.getValues = function () {
         var objResult = [];
         this.find(".pickListResult option").each(function () {
            objResult.push({
               id: $(this).data('id'),
               text: this.text
            });
         });
         return objResult;
      };

      this.init = function () {
         var pickListHtml =
                 "<div  class='row'>" +
                 "  <div style='float:left;width:30%' class='col-sm-5'>" +
                 "	 Available Countries<select style='width:100%' class='form-control pickListSelect pickData' multiple></select>" +
                 " </div>" +
                 " <div style='float:left;width:10%;text-align:center' class='col-sm-2 pickListButtons'>" +
                 "	<br><a style='cursor:pointer' class='pAdd'>Add</a><br>" +
                 "  <a style='cursor:pointer' class='pAddAll'></a><br>" +
                 "	<a style='cursor:pointer' class='pRemove'>Remove</a><br>" +
                 "	<a style='cursor:pointer' class='pRemoveAll'></a><br>" +
                 " </div>" +
                 " <div style='float:left;width:30%' class='col-sm-5'>" +
                 "  Selected Countries<select style='width:100%' name='country[]' id='country' class='form-control pickListSelect pickListResult' multiple></select>" +
                 " </div>" +
                 "</div>";

         this.append(pickListHtml);

         this.fill();
         this.controll();
      };

      this.init();
      return this;
   };

   $.fn.pickList.defaults = {
      add: 'Add',
      addAll: 'Add All',
      remove: 'Remove',
      removeAll: 'Remove All'
   };


}(jQuery));
