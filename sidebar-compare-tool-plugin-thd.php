<?
/*
 * THD code
 * Widget Name: sidebar-compare-tool-plugin-thd
 * Version: 1.0
 * Developed By: Ernesto Saborio Cordoba
 * Collaborators:
 * Date of Create: 05/03/23

 * Class Dependencies: .member_results .search_result .mid_section a .mid_section span .info_section 
 */ ?>

<div class="module">
	<form action="/compare" method="GET">
		<input type="hidden" id="input_members_ids" name="members" value="">
		<div class="row">
			<div class="selected-members col-md-12">

			</div>	
			<div class="col-md-12">
				<button id="compareSelectedBtn" type="submit" href="#" class="btn btn-primary btn-block" disabled><b>Compare Selected Members</b></button>
			</div>
		</div>
	</form>
</div>

<script>
	let compareSelectedMembers =  getCookie("selectedMembers");
	if(compareSelectedMembers !== ''){
		compareSelectedMembers = JSON.parse(compareSelectedMembers);
		updateSelectedMembersInputs();
	}else{
		compareSelectedMembers = [];
	}

	$(".member_results .search_result").each(function(){
		let memberName = $(this).find(".mid_section a").attr("title");
		let memberId = $(this).find(".mid_section span").data("userid");
		let isChecked = "";
		if(isSelectedMember(memberId)){
			isChecked = "checked";
		}
		$html = '<div class="module nomargin fpad text-center">';
		$html += '	<div class="form-check">';
		$html += '		<input class="form-check-input chbxCompareMembers" type="checkbox" value="' + memberId + '" data-name="' + memberName + '" id="compareMember' + memberId + '" onclick="clickCompareMembers(this)" '+ isChecked +'>';
		$html += '		<label class="form-check-label" for="compareMember' + memberId + '">Compare</label>';
		$html += '	</div>';	
		$html += '</div>';
		$(this).find(".info_section").append($html);
	});
	
	$('.member_results').on('DOMSubtreeModified', function(){
		$(".member_results .search_result").each(function(){
			if(  !$(this).find(".info_section .chbxCompareMembers").length )  
			{
				let memberName = $(this).find(".mid_section a").attr("title");
				let memberId = $(this).find(".mid_section span").data("userid");
				$html = '<div class="module nomargin fpad text-center">';
				$html += '	<div class="form-check">';
				$html += '		<input class="form-check-input chbxCompareMembers" type="checkbox" value="' + memberId + '" data-name="' + memberName + '" id="compareMember' + memberId + '"  onclick="clickCompareMembers(this)">';
				$html += '		<label class="form-check-label" for="compareMember' + memberId + '">Compare</label>';
				$html += '	</div>';	
				$html += '</div>';
				$(this).find(".info_section").append($html);
			}		
		});

	});	
	
	updateSelectedMembersInputs();
	

	
	function clickCompareMembers (chbx){	
		let memberId = $(chbx).val();
		let memberName = $(chbx).data("name");
		
		if($(chbx).is(':checked')){		
			if(compareSelectedMembers.length === 3){
				$(chbx).removeAttr("checked");
			}else{
				addSelectedMember({"name": memberName, "id": memberId});
			}			
		}else{
			removeSelectedMember(memberId);
		}
		updateSelectedMembersInputs();
	}	
	
	function selectedMemberChbx(chbx){
		let memberId = $(chbx).val();
		removeSelectedMember(memberId);
		$("#compareMember" + memberId).trigger("click");
		updateSelectedMembersInputs();
	}
	
	function setCookie(cname, cvalue, exdays) {
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	  let expires = "expires="+d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
	  let name = cname + "=";
	  let ca = document.cookie.split(';');
	  for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
		  c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		  return c.substring(name.length, c.length);
		}
	  }
	  return "";
	}	
	
	function addSelectedMember(element){
		compareSelectedMembers.push(element);
		setCookie('selectedMembers', JSON.stringify(compareSelectedMembers), 1);
	}
	
	function removeSelectedMember(id){
		let changedSeletedMembers = [];
		compareSelectedMembers.forEach(function(item){
			if(item.id != id){
				changedSeletedMembers.push(item);
			}
			
		});
		compareSelectedMembers = changedSeletedMembers;
		setCookie('selectedMembers', JSON.stringify(compareSelectedMembers), 1);
	}
	
	function isSelectedMember(id){
		let isSeletedMembers = false;
		compareSelectedMembers.forEach(function(item){
			if(item.id != id){
				isSeletedMembers = true;
			}
			
		});
		return isSeletedMembers;
	}	
	
	function updateSelectedMembersInputs(){
		
		$(".chbxCompareMembers").each(function(){
			$(this).removeAttr("checked");
		});
		  		 		  
		$(".selected-members").html("");
		let membersIds = [];
		
		compareSelectedMembers.forEach(function(item){
			let memberId = item.id;
			let memberName = item.name;
			let html = '<div class="form-check"  id="compareSelectedMember' + memberId + '">' +
				'<input class="form-check-input selectedMemberChbx" type="checkbox" value="'+ memberId +'" id="selectedMember' + memberId + '" onclick="selectedMemberChbx(this)"  data-name="' + memberName + '"  checked>' +
				'<label class="form-check-label" for="selectedMember' + memberId + '">&nbsp' +
				memberName + 
				'</label>' +
				'</div>';
			$(".selected-members").append(html);				
			$('#compareMember' + memberId).attr("checked", true);
			membersIds.push(memberId);
		});	
		
		
		$("#input_members_ids").val(membersIds.join(","));
		
		if(compareSelectedMembers.length > 1){
			$("#compareSelectedBtn").removeAttr("disabled");
		}else{
			$("#compareSelectedBtn").attr("disabled", "disabled");
		} 		
	}
	
											 											
</script>