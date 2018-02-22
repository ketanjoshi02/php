//assign leads to user
$(document).ready(function(){
	$('.leads-assignments').click(function(){
		//alert();
		 $.ajax
			({ 
				url: 'lead-assignments.php',
				data: "",
				type: 'post',
				beforeSend: function() {
	              $("#loading-image").show();
	            },
				success: function(result)
				{
					$("#loading-image").hide();
					$('#display-area').html(result);
				}
			});
	});
});
//Change-password
$(document).ready(function(){
	$('.change-password').click(function(){
		//alert();
		 $.ajax
			({ 
				url: 'change-password.php',
				data: "",
				type: 'post',
				beforeSend: function() {
	              $("#loading-image").show();
	            },
				success: function(result)
				{
					$("#loading-image").hide();
					$('.dbalist').html(result);
				}
			});
	});
});

function DocDescEdit(doc_id,doc_desc){
	var new_desc = $('#editable'+doc_id).text();
			 $.ajax
				({ 
					url: 'docdescedit.php',
					data:{doc_id : doc_id, new_desc : new_desc} ,
					type: 'post',
					beforeSend: function() {
		              $("#loading-image").show();
		            },
					success: function(result)
					{
						$("#loading-image").hide();
						$(".editable"+doc_id).html(result);
					}
				});
		}

/*-----------------------------------------------------DELETE FUNCTIONS------------------------------------------*/

//to delete lead from leads list	
function delete_lead(leadID){
	
	var lead_id = leadID;
	
	 $.ajax
		({ 
			url: 'delete-lead.php',
			data:{ lead_id : lead_id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});
	}
//confirm dialogue box before delete lead
function confirmDelete(leadid){
	var leadID = leadid;
    var choice = confirm('Do you really want to delete this lead?');
    if(choice === true) {
        delete_lead(leadID);
		return true;
    }
    return false;	
}

//delete user
function delete_user(user_id){
	 $.ajax
		({ 
			url: 'delete-user.php',
			data:{ user_id : user_id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});
	}
//confirm before delete user
function confirmDeleteUser(user_id){
    var choice = confirm('Do you really want to delete this user?');
    if(choice === true) {
        delete_user(user_id);
		return true;
    }
    return false;	
}
//delete DBA
function delete_dba(dbaid){
	 $.ajax
		({ 
			url: 'delete-dba.php',
			data:{ dbaid : dbaid},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});
	}
//confirm before delete DBA
function confirmDeleteDBA(dbaid){
    var choice = confirm('Do you really want to delete this user?');
    if(choice === true) {
        delete_dba(dbaid);
		return true;
    }
    return false;	
}

//delete Document
function delete_doc(doc_id,cat){
	 $.ajax
		({ 
			url: 'delete-doc.php',
			data:{ doc_id : doc_id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				$('#documents-by-category').html(function(){
					$.ajax
					({
						url:'documents-by-category.php',
						data:{cat: cat},
						type:'post',
						success: function(result)
						{
							$('#documents-by-category').html(result);
						}
					});
				});
			}
		});
	}
//confirm before delete Document
function confirmDeleteDoc(doc_id,cat){
    var choice = confirm('Do you really want to delete this Document?');
    if(choice === true) {
        delete_doc(doc_id,cat);
		return true;
    }
    return false;	
}

//delete Equipment
function delete_equipment(id){
	 $.ajax
		({ 
			url: 'delete-equipment.php',
			data:{equi_id : id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});
	}
//confirm before delete Equipment
function confirmDeleteEquipment(id){
    var choice = confirm('Do you really want to delete this user?');
    if(choice === true) {
        delete_equipment(id);
		return true;
    }
    return false;	
}

function confirmDeleteAgr(id,user_comp_id){
    var choice = confirm('Do you really want to delete this user?');
    if(choice === true) {
        delete_agreement(id,user_comp_id);
		return true;
    }
    return false;	
}
function delete_agreement(id,user_comp_id){
	 $.ajax
		({ 
			url: 'delete-agreement.php',
			data:{agr_id: id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				$('.dbalist').html(function() {
					$.ajax
						({ 
							url: 'view-agreements.php',
							data:{comp_id : user_comp_id},
							type: 'post',
							beforeSend: function() {
						              $("#loading-image").show();
						           },
							success: function(result)
							{
								//alert(result);
								$("#loading-image").hide();
								$(".dbalist").html(result);
							}
						});
				});
			}
		});

}



//delete user
function delete_distributor_application(dist_id){
	 $.ajax
		({ 
			url: 'delete-distributor-application.php',
			data:{ dist_id : dist_id},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});
	}
//confirm before delete user
function confirmDeleteDistApp(dist_id){
    var choice = confirm('Do you really want to delete this Distributor Application?');
    if(choice === true) {
        delete_distributor_application(dist_id);
		return true;
    }
    return false;	
}
/*--------------------------------------------------------------*/

//edit lead when click on edit button in leads list
function lead_edit_form(leadID,dealerName,currentUrl,param,text){

	var lead_id = leadID;
	var dealer = dealerName;
	var cururl = currentUrl	 
		$.fancybox.open({
	        href: "lead-form-edit.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	            data:{ lead_id : lead_id, dealer : dealer, cururl : cururl, param : param, text : text},
				success: function(result)
				{
					
				}
	        }
	    });

	}
//edit user when click on edit button in user list
function edit_user_form(userID){
	var user_id = userID;
		$.fancybox.open({
	        href: "edit-user.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	            data:{ user_id : user_id},
				success: function(result)
				{
					
				}
	        }
	    });
	}
//To see user login history
$(document).ready(function(){
	$('.user-login-report').click(function(){
		//alert();
		$.fancybox.open({
	        href: "user-login-report.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	            data:"",
				success: function(result)
				{
					
				}
	        }
	    });
	});
});



//edit distributor application when click on edit button in DA list
function edit_dist_form(distID){
	var dist_id = distID;
		$.fancybox.open({
	        href: "edit_dist_form.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	            data:{ dist_id : dist_id},
				success: function(result)
				{
					
				}
	        }
	    });
	}
//to view documents attached with compressor model number when click on compressor model number in equipment assessment
function comp_model_doc(comp_model_no){
	var comp_model_no = comp_model_no;
	//alert();
	 $.ajax
		({ 
			url: 'comp_model_detail.php',
			data: {comp_model_no: comp_model_no},
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$('#serched-equipment-values').html(result);
			}
		});
}
//function to edit equipment detail
function editEquipment(id){
	//alert(id)
		$.fancybox.open({
	        href: "edit-equipment.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	          	data:{ equi_id : id},
				success: function(result)
				{
					
				}
	        }
	    });
	}
function updateEqui(){
	 $.ajax
		({ 
			url: 'update-equipment.php',
			data:$('#equipmentupdateform').serialize(),
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$("#display-return-msg").html(result);
				$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			}
		});

}

function totalLeads(){
	 $.ajax
		({ 
			url: 'total-leads.php',
			data:"",
			type: 'post',
			beforeSend: function() {
              $("#loading-image").show();
            },
			success: function(result)
			{
				$("#loading-image").hide();
				$('.dbalist').html(result);
			}
		});

}

/*-------------------------------------------------------------*/
//DBA Detail page
function dbaDetail(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'dbadetailpage.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//this function displays lead records for specific dba
function viewdbaLeads(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'viewdbaLeads.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//this function displays records of website leads
function webLeads(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'webleads.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

//this function displays records of website Distributor applications
function webdistApps(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'distributor-applications.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//this function use to get new dba adding form
function addDBA(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'add-user-company.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//this function adds dba detail in database 
function saveDBA(){
	//alert();
	 $.ajax
		({ 
			url: 'save-dba.php',
			data: $('#adddbaform').serialize(),
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$('#display-return-msg').html(result);
				$('#display-return-msg').fadeIn("slow").delay(5000).fadeOut("slow");
				
			}
		});
}
//this function triggers when click on inactive leads and draw results 30 60 90 days inactive leads
function salesActivity(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'sales-activity.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//this function use to get new lead adding form
function addNewLead(sendval){
	//alert();
	 $.ajax
		({ 
			url: 'lead-form.php',
			data: "",
			type: 'post',
			data: {sendval: sendval},
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//function use to add lead into database
function saveLead(page){
	//alert();
	 $.ajax
		({ 
			url: 'save-lead.php',
			data: $('#add-lead-form').serialize(),
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				//$('#display-return-msg').html(result);
				//$('#display-return-msg').fadeIn("slow").delay(5000).fadeOut("slow");
				if(result == 1){
					if(page != ""){
						viewdbaLeads(page);
					}
					else{
						totalLeads();	
					}				
				}
				else{
					if(confirm("Lead will be saved as draft.\nOr please fill all the required fields.")){
						if(page != ""){
							viewdbaLeads(page);
						}
						else{
							totalLeads();	
						}
					}
					else{
						return false;
					}	
				}
			}
		});
}
//function use to view users DBA wise
function viewUser(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'view-all.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});

}
//function used to get new user form to add
function addNewUser(){
	//alert();
	 $.ajax
		({ 
			url: 'add-user.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//saves user details asynchroniously.
function saveUser(){
	//alert();
	 $.ajax
		({ 
			url: 'save-user-details.php',
			data: $('#adduserform').serialize(),
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$('#display-return-msg').html(result);
				$('#display-return-msg').fadeIn("slow").delay(5000).fadeOut("slow");
				
			}
		});
}

//function used to get to the view/download page category wise 
function documentDetail(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'document.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//archived leads in corporate dba
function arcLeads(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'archived-leads.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//30 days inactive leads
function thirtyDays(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'thirty-days.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//60 days inactive leads
function sixtyDays(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'sixty-days.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//90 days inactive leads
function ninetyDays(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'ninety-days.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//basic search equipment assessment
function basicSearch(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'oil-charge.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

//advance search equipment assessment
function adSearch(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'advance-search.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//manage DBA folder
/* function manageDBA(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'manage-dba.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}*/
//list of all DBAs in table format with edit delete option
function dbaList(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'user-company-list.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
function editDBA(user_comp_id){
	//alert(user_comp_id);
	
		$.fancybox.open({
	        href: "edit-user-company.php",
	        type: "ajax",
	        ajax: {
	            type: "POST",
	            data:{comp_id: user_comp_id},
				success: function(result)
				{
					
				}
	        }
	    });
}

function unapprovedLeads(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'unapproved-leads.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

function aggrDetail(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'aggr-detail.php',
			data:{comp_id : user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

function uploadAgreements(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'upload-agreements.php',
			data:{comp_id : user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
function viewAgreements(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'view-agreements.php',
			data:{comp_id : user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}



/*----------------------------------------------------------------------BREADCRUMBS--------------------------------*/

//this function adds breadcrumb for name of dba
function breadCrumb(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'addbreadcrumb.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//this function adds breadcrumb for dba leads
function breadCrumb2(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'addbreadcrumb2.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}
//this function adds breadcrumb for website/email inquiries
function breadWebLeads(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadwebleads.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}

//this function adds breadcrumb for website/email inquiries for Distributor application
function breadWebdistApps(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadwebdistapps.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}

//breadcrumbs for inactive leads
function salesBread(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'salesbread.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}
//breadcrumb for user list in dba detail page
function userBread(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'userbread.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}
//breadcrumb for document view category
function docBread(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'docbread.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//breadcrumb for basicsearch
function equiBread(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'eqibread.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//breadcrumb for advance search
function equiBread2(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'eqibread2.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//add equipment form
function addEqipment(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'add-equipment.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}
//archived leads breadcrumb
function arcBread(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'arcbread.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}
//breadcrumb for inactive leads
function inactiveBread(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'inactive-bread.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//breadcrumb for 30 days inactive leads
function bread30(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'bread30.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb3").html(result);
			}
		});
}
//breadcrumb for 60 days inactive leads
function bread60(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'bread60.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb3").html(result);
			}
		});
}
//breadcrumb for 90 days inactive leads
function bread90(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'bread90.php',
			data: {comp_id: user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb3").html(result);
			}
		});
}
//breadcrumb for manage DBA
function breadManageDBA(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadmanagedba.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
//breadcrumb for agreement detail
function breadAggr(comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadaggr.php',
			data: {comp_id: comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}

//breadcrumb for agreement upload
function breaduploadagr(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breaduploadagr.php',
			data:{comp_id : user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb3").html(result);
			}
		});
}
//breadcrumb for view agreements
function breadviewagr(user_comp_id){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadviewagr.php',
			data:{comp_id : user_comp_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb3").html(result);
			}
		});
}

//=================================================== Added by Ketan afterwards =======================================================
/* import & export step-1 */
// Manage import and export
function manageImportExport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'manageimportexport.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

// breadcrumb for Import Export
function breadImportExport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadimportexport.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}
/* import & export step-2 */
// Manage export
function manageImport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'manageimport.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

// breadcrumb for Import Export
function breadImport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadimport.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}
/* import & export step-3 */
// Manage export
function manageExport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'manageexport.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result);
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

// breadcrumb for Import Export
function breadExport(){
	//alert(user_comp_id);
	 $.ajax
		({ 
			url: 'breadexport.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				//alert(result); 
				$("#loading-image").hide();
				$("#breadcrumb2").html(result);
			}
		});
}

// Change status if deleted leads viewed by user // Change status if added leads viewed by user 
function lead_notifi_counter_change(thisObj,leadArchiveId){
		
		var lead_archive_id = leadArchiveId;
		var counter = $(".lead_notify").text();
		
		$.ajax
		({ 
			url: 'change_lead_notify_status.php',
			data: {lead_archive_id : lead_archive_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				$("#loading-image").hide();
				$(".lead_notify").text(counter - 1);
				$(thisObj).parent().next().remove();
				$(thisObj).parent().next().remove();
				$(thisObj).parent().remove();
			}
		});		
}

// Notification counter change for follow-up Date of lead
function follow_up_date_counter_change(thisObj,leadId){
	
		var lead_id = leadId;
		var counter = $(".lead_notify").text();
		
		$.ajax
		({ 
			url: 'change_follow_up_date_notify_status.php',
			data: {lead_id : lead_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result){
				$("#loading-image").hide();
				$(".lead_notify").text(counter - 1);
				$(thisObj).parent().next().remove();
				$(thisObj).parent().next().remove();
				$(thisObj).parent().remove();
			}
		});
}

// Clear Follow-up Date for lead
function clear_follow_up_date(leadId){
	
		var lead_id = leadId;
		
		$.ajax
		({ 
			url: 'clear_follow_up_date.php',
			data: {lead_id : lead_id},
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result){
				$("#loading-image").hide();
				$('.form-group #follow-up-date').parent().parent().next().remove();
				$('.form-group #follow-up-date').parent().parent().addClass('col-xs-7');
				$('.form-group #follow-up-date').parent().parent().removeClass('col-xs-6');
				$('.form-group #follow-up-date').val("");
			}
		});
}

// Set follow up notification and counter
/*function follow_up_date_set_notification(leadId){
	
		var lead_id = leadId;
		var counter = $(".lead_notify").text();
		
		$.ajax
		({ 
			url: 'follow_up_date_notification.php',
			data: {lead_id : lead_id},
			type: 'post',
			success: function(result){
				$(".lead_notify").text(counter + result.counter);
				$(".dropdown-alerts").prepend(result.val);
			}
		});
}
*/

// Manage watch list
function manageWatchList(){
	 $.ajax
		({ 
			url: 'managewatchlist.php',
			data:"",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{
				$("#loading-image").hide();
				$(".dbalist").html(result);
			}
		});
}

// breadcrumb for Watch List
function breadWatchList(){
	 $.ajax
		({ 
			url: 'breadwatchlist.php',
			data: "",
			type: 'post',
			beforeSend: function() {
		              $("#loading-image").show();
		           },
			success: function(result)
			{ 
				$("#loading-image").hide();
				$("#breadcrumb").html(result);
			}
		});
}

//confirm dialogue box before remove lead from watch list
function confirmWatchListRemove(leadid){
	var leadID = leadid;
    var choice = confirm('Do you really want to remove this lead from watch list?');
    if(choice === true) {
        remove_watch_list(leadID);
		return true;
    }
    return false;	
}

//to delete lead from leads list	
function remove_watch_list(leadID){
	var lead_id = leadID;
	$.ajax
	({ 
		url: 'remove_watch_list.php',
		data:{ lead_id : lead_id},
		type: 'post',
		beforeSend: function() {
			  $("#loading-image").show();
			},
		success: function(result)
		{
			$("#loading-image").hide();
			$("#display-return-msg").html(result);
			$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
			manageWatchList();
		}
	});
}