function buddyRemoved(b){var a=b.data.buddyid;$.ajax({type:"POST",url:"doRemoveBuddy.php",data:"buddyid="+a});$("#buddyremove_"+a).fadeOut("slow");window.parent.miaChat.removeBuddyFromList(a)}function getBuddies(){$.getJSON("getBuddies.php",function(a){var c='<h2>Active Buddy List</h2><table id="activeBuddyListTable" border="1"><thead><tr><th>Fullname</th><th>Username</th><th>Email</th><th>Remove</th></tr></thead>';$("#activeBuddyList h2").remove();$("#activeBuddyListTable").remove();$("#activeBuddyList").append(c);$.each(a,function(h,j){var e=a[h].bid,f=a[h].full_name,k=a[h].username,g=a[h].email,d;d='<tr id="buddyremove_'+e+'"><td>'+f+"</td><td>"+k+"</td><td>"+g+'</td><td class="removeContact"><img id="rmbuddyid_'+e+'" src="images/famfamfam_silk_icons/user_delete.png" alt="Remove buddy" /></td></tr>';$("#activeBuddyListTable").append(d);$("#rmbuddyid_"+e).bind("click",{buddyid:e},buddyRemoved)});var b="</table>";$("#activeBuddyList").append(b)})}function buddyAdded(b){var a=b.data.buddyid;$.ajax({type:"POST",url:"doAddBuddy.php",data:"buddyid="+a});$("#buddyrow_"+a).fadeOut("slow");getBuddies();window.parent.miaChat.getBuddies()}function buddySearch(c){var d=$("#username").val(),a=$("#fullname").val(),b=$("#email").val();if(d===""&&a===""&&b===""){return false}else{$.getJSON("searchBuddies.php",{username:d,fullname:a,email:b},function(f){var e='<p>Search Results</p><table id="searchResultsTable" border="1"><thead><tr><th>Fullname</th><th>Username</th><th>Email</th><th>Add</th></tr></thead>';$("p").remove();$("#searchResultsTable").remove();$("#searchResults").append(e);$.each(f,function(m,n){var h=f[m].bid,j=f[m].full_name,o=f[m].username,l=f[m].email,g,k;g='<tr id="buddyrow_'+h+'"><td>'+j+"</td><td>"+o+"</td><td>"+l+'</td><td class="addContact"><img id="buddyid_'+h+'" src="images/famfamfam_silk_icons/user_add.png" alt="Add buddy" /></td></tr>';$("#searchResultsTable").append(g);$("#buddyid_"+h).bind("click",{buddyid:h},buddyAdded)});buddyTableFooter="</table>";$("#searchResults").append(buddyTableFooter)})}}$(document).ready(function(){$("#addBuddyButton").bind("click",{},buddySearch);getBuddies();$("#username").focus()});