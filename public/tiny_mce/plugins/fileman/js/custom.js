/*
  RoxyFileman - web based file manager. Ready to use with CKEditor, TinyMCE. 
  Can be easily integrated with any other WYSIWYG editor or CMS.

  Copyright (C) 2013, RoxyFileman.com - Lyubomir Arsov. All rights reserved.
  For licensing, see LICENSE.txt or http://RoxyFileman.com/license

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  Contact: Lyubomir Arsov, liubo (at) web-lobby.com
*/

var input_name_for_image_file_manager = '';

function FileSelected(file){
  /**
   * file is an object containing following properties:
   * 
   * fullPath - path to the file - absolute from your site root
   * path - directory in which the file is located - absolute from your site root
   * size - size of the file in bytes
   * time - timestamo of last modification
   * name - file name
   * ext - file extension
   * width - if the file is image, this will be the width of the original image, 0 otherwise
   * height - if the file is image, this will be the height of the original image, 0 otherwise
   * 
   */
  //alert('"' + file.fullPath + "\" selected.\n To integrate with CKEditor or TinyMCE change INTEGRATION setting in conf.json. For more details see the Installation instructions at http://www.roxyfileman.com/install.");
	var fieldId = RoxyUtils.GetUrlParam('txtFieldId');
	$(window.parent.document).find('#path_' + fieldId).attr('value', file.fullPath);
	$(window.parent.document).find('#img_' + fieldId).attr('src', file.fullPath);

	window.parent.closeCustomRoxy();
}
function GetSelectedValue(){
  /**
  * This function is called to retrieve selected value when custom integration is used.
  * Url parameter selected will override this value.
  */
  return "";
}


function openCustomRoxy(input_name, button) {

	closeCustomRoxy();

	const iframe = $(`
        <div id="iframe_${input_name}">
            <iframe
                src="/tiny_mce/plugins/fileman/index.html?integration=custom&type=files&txtFieldId=${input_name}&_token=${window.csrfToken}"
                style="width:100%;height:100%;border:0">
            </iframe>
        </div>
    `);

	$('body').append(iframe);

	input_name_for_image_file_manager = input_name;

	iframe.dialog({
		modal: true,
		width: 875,
		height: 600,
		close: function () {
			$(this).dialog('destroy').remove();
			input_name_for_image_file_manager = '';
		}
	});
}

/*function openCustomRoxy(input_name='roxyCustomPanel2',_select_image){

	let iframe_html = `<div id="iframe_`+input_name+`">
		<iframe src="/tiny_mce/plugins/fileman/index.html?integration=custom&type=files&txtFieldId=`+input_name+`&_token=`+window.csrfToken+`" style="width:100%;height:100%" frameborder="0"></iframe>
		</div>`;

	$(_select_image).parent().after(iframe_html);

	input_name_for_image_file_manager = input_name;
	$('#iframe_'+input_name).dialog({modal:true, width:875,height:600});
}*/



function closeCustomRoxy() {

	if (!input_name_for_image_file_manager) {
		return;
	}

	const $dlg = $('#iframe_' + input_name_for_image_file_manager);

	if ($dlg.length) {

		if ($dlg.hasClass('ui-dialog-content')) {
			$dlg.dialog('destroy');
		}

		$dlg.remove();
	}

	input_name_for_image_file_manager = '';
}