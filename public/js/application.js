

//global functions -> for common access
var OriginalData = [];
function removeData(index){
    OriginalData.splice(index,1);
    if(OriginalData.length == 0){
        document.getElementById("update-btn").hidden = true;
    }
}
function ReadToWrite(data){
    for(var x = 0; x < data.length; x++) {
        data[x].readOnly = false;
        data[x].hidden = false;
    }
    data[0].readOnly = true
}
function WriteToRead(data){
    for(var x = 0; x < data.length; x++) {
        data[x].readOnly = true;
    }
}
function setHidden(data,indices){
    indices.forEach( index=> {
        data[index].hidden = true
    });
}
function setVisible(data,indices){
indices.forEach( index=> {
    data[index].hidden = false
});
}
function getTableData(event){
    var rowid = event.target.value;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    return data;
}

function cancelEditActions(event,data){
    var rowid = event.target.value;
    var index = 0;
    for(var x =0;x<OriginalData.length;x++){
        if(OriginalData[x].id == rowid){
            index = x;
        }
    }
    WriteToRead(data);
    return index;
}
function previewImage(event,actionFrom){
    var rowid = event.target.value;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    var input = data[8];
    var preview = data[7]; 
    if(actionFrom == "add"){
        input = document.getElementById("imageinput2");
        preview = document.getElementById("imagepreview2"); 
    } 
    const file = input.files[0];
    preview.hidden = true;  
    
    if (file) {
        
        preview.hidden = false;
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
function getSelectedIndexByValue(selectElement, value) {
    for (var i = 0; i < selectElement.options.length; i++) {
      if (selectElement.options[i].value === value) {
        return i;
        
      }
    }
    // If the value is not found, you may handle it accordingly (e.g., set a default index)
    return -1;
  }
//page specific functions

//for editing
function userEditMode(event){
    
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    var user ={
        id: data[0].value,
        fname:data[1].value,
        lname:data[2].value,
        hCode:data[3].selectedIndex,
        role:data[5].selectedIndex,
        contact:data[7].value,
        email:data[8].value,
        password:data[9].value
    }
    OriginalData.push(user);
    ReadToWrite(data);
    setHidden(data,[3,5,11,12]);
    var hCodeName = data[3].name;
    var roleName = data[5].name;
    data[4].selectedIndex  = getSelectedIndexByValue(data[4],hCodeName);
    data[6].selectedIndex  = getSelectedIndexByValue(data[6],roleName);
    
}
function businessEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    
    var business={
        id: data[0].value,
        name:data[1].value,
        businessType:data[2].value,
        businesscontact:data[4].value,
        businessemail:data[5].value,
    }
    OriginalData.push(business);
    ReadToWrite(data);
    setHidden(data,[2,6,7,9,10]);
    var bTypeName = data[2].name;
    data[3].selectedIndex  = getSelectedIndexByValue(data[3],bTypeName);
}
function roleEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var role={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(role);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function bTypeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var businessType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(businessType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function HcodeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var hcode={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(hcode);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function addressTypeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var aType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(aType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function firmTypeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var firmType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(firmType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function postOfficeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var postOffice={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(postOffice);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function stateCodeEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var stateCode={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(stateCode);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function packageEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var package={
        id: data[0].value,
        code:data[1].value,
        name:data[2].value,
        userlimit:data[3].value
    }
    OriginalData.push(package);
    ReadToWrite(data);
    setHidden(data,[4,6]);
    setVisible(data,[5]);
}
function packageBaseEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var packageBase={
        id: data[0].value,
        packagename:data[1].value,
        duration:data[2].value,
        baseprice:data[3].value
    }
    OriginalData.push(packageBase);
    ReadToWrite(data);
    setHidden(data,[1,5,7]);
    setVisible(data,[2,6]);
    var packageName = data[1].name;
    data[2].selectedIndex = getSelectedIndexByValue(data[2], packageName);
    
}
function bUserEdit(event){
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".column-data");
    var bUser={
        id: data[0].value,
        bName:data[1].value,
        uEmail:data[2].value
    }
    OriginalData.push(bUser);
    ReadToWrite(data);
    setHidden(data,[1,3,5,7]);
    setVisible(data,[2,4,6]);
    var businessName = data[1].name;
    data[2].selectedIndex = getSelectedIndexByValue(data[2], businessName);
    var userEmail = data[3].name;
    data[4].selectedIndex = getSelectedIndexByValue(data[4], userEmail);
    
}
function postCodeEdit(event){
    
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    var postcode ={
        id: data[0].value,
        postcode:data[1].value,
        location:data[2].value,
        postoffice:data[3].value,
        statecode:data[5].value,
    }
    OriginalData.push(postcode);
    ReadToWrite(data);
    setHidden(data,[3,5,7,8]);
    setVisible(data,[4,6,9]);
    var hCodeName = data[3].name;
    var roleName = data[5].name;
    data[4].selectedIndex  = getSelectedIndexByValue(data[4],hCodeName);
    data[6].selectedIndex  = getSelectedIndexByValue(data[6],roleName);
}
function addressEdit(event){
    
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    var address ={
        id: data[0].value,
        useremail:data[1].value,
        addresstype:data[3].value,
        addressline:data[5].value,
        street:data[6].value,
        state:data[7].value,
        office:data[9].value,
        postcode:data[11].value,
    }
    OriginalData.push(address);
    ReadToWrite(data);
    setHidden(data,[1,3,7,9,13,14]);
    setVisible(data,[2,4,8,10,15]);
    data[12].readOnly = true;
    data[11].readOnly = true;
    var addresstype = data[3].value;
    var state = data[7].value;
    var office = data[9].value;
    var code = data[11].value;
    handleStateCodeChange(data[7],event);
    data[4].selectedIndex  = getSelectedIndexByValue(data[4],addresstype);
    data[8].selectedIndex  = getSelectedIndexByValue(data[8],state);
}
//for cancelling edit
function cancelUserEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].fname;
    data[2].value = OriginalData[index].lname;
    data[7].value = OriginalData[index].contact;
    data[8].value = OriginalData[index].email;
    data[9].value = OriginalData[index].password;
    setHidden(data,[4,6,13]);
    setVisible(data,[3,5,11,12]);
    removeData(index);
}
function cancelBusinessEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    data[2].value = OriginalData[index].businessType;
    data[4].value = OriginalData[index].businesscontact;
    data[5].value = OriginalData[index].businessemail;
    setVisible(data,[2,6,9,10]);
    setHidden(data,[3,7,8,11]);
    removeData(index);
    data[7].src = "";
    data[8].value = "";
}
function cancelRoleEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelHcodeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelbTypeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function canceladdressTypeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelfirmTypeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelpostOfficeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelstateCodeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelPackageEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].code;
    data[2].value = OriginalData[index].name;
    data[3].value = OriginalData[index].userlimit;
    setHidden(data,[5]);
    setVisible(data,[4,6]);
    removeData(index);
}
function cancelPackageBaseEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].packagename;
    data[2].value = OriginalData[index].duration;
    data[3].value = OriginalData[index].baseprice;
    setHidden(data,[2,6]);
    setVisible(data,[1,5,7]);
    removeData(index);
}
function cancelbUserEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].bName;
    data[2].value = OriginalData[index].uEmail;
    setHidden(data,[2,4,6]);
    setVisible(data,[1,3,5,7]);
    removeData(index);
}
function cancelpostCodeEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].postcode;
    data[2].value = OriginalData[index].location;
    data[3].value = OriginalData[index].postoffice;
    data[5].value = OriginalData[index].statecode;
    setHidden(data,[4,6,9]);
    setVisible(data,[3,5,7,8]);
    removeData(index);
}
function cancelAddressEdit(event){
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].useremail;
    data[3].value = OriginalData[index].addresstype;
    data[5].value = OriginalData[index].addressline;
    data[6].value = OriginalData[index].street;
    data[7].value = OriginalData[index].state;
    data[9].value = OriginalData[index].office;
    data[11].value = OriginalData[index].postcode;
    data[10].options.length = 0;
    var element = data[10];
    var defaultOption = document.createElement("option");
    defaultOption.text = "Auto-Generated";
    element.add(defaultOption);
    setHidden(data,[2,4,8,10,15]);
    setVisible(data,[1,3,7,9,13,14]);
    removeData(index);
}
function handleStateCodeChange(element,event) {
    var selectedStateCode = element.value;
    
    var rowcount = element.id.replace('stateCode', '');
    if(event != "Add"){
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".column-data");
    }
    // Make an AJAX request to fetch post offices based on the selected state code
    axios.get('/get-post-offices/' + selectedStateCode)
        .then(function(response) {
            var postOffices = response.data;
            
            // Update the content of the existing 'postOffice' dropdown
            var postOfficeDropdown = document.getElementById('postOfficeOption' + rowcount);
            if(event == "Add"){
                postOfficeDropdown = document.getElementById('new-address-postoffice');
            }
            // Clear existing options
            postOfficeDropdown.innerHTML = '';
            
            postOffices.forEach(function(postOffice) {
                var option = document.createElement('option');
                option.text = postOffice;
                postOfficeDropdown.add(option);
            });
            if(event == "Add"){
                var element = document.getElementById('new-address-postoffice');
                handlepostOfficeChange(element,event);
            }
            else{
            handlepostOfficeChange(data[10],2);
            }
            
        })
        .catch(function(error) {
            console.error('Error fetching post offices', error);
        });
        
}   
function handlepostOfficeChange(element,event) {
    var selectedPostOffice = element.value;
    if(event == 1){
    var rowcount = element.id.replace('postOfficeName', '');
    }
    else{
        var rowcount = element.id.replace('postOfficeOption', '');
    }
   
    // Make an AJAX request to fetch post offices based on the selected state code
    axios.get('/get-post-code/' + selectedPostOffice)
        .then(function(response) {
            var postCode = response.data;
            
            // Update the content of the existing 'postOffice' dropdown
            var postcodebox = document.getElementById('postcode' + rowcount);
            if(event == "Add"){
                postcodebox = document.getElementById('new-address-postcode');
            }
            // Clear existing options
            postcodebox.value = postCode;
        })
        .catch(function(error) {
            console.error('Error fetching post code', error);
        });
}   
//for popups
function showAddBox(){
    document.getElementById("add-box").hidden = false;
    document.getElementById("edit-box").hidden = true;
    document.getElementById("newBtn").hidden = true;
}
function showAddBox(htmlelement) {
    document.getElementById("add-box").hidden = false;
    document.getElementById("edit-box").hidden = true;
    document.getElementById("newBtn").hidden = true;
    var element = document.getElementById(htmlelement);
    handleStateCodeChange(element,"Add");
}
function closeAddBox() {
    document.getElementById("add-box").hidden = true;
    document.getElementById("edit-box").hidden = false;
    document.getElementById("newBtn").hidden = false;
}
function showPopup() {
    document.getElementById("displaypopup").hidden = false;
}
function closePopup() {
    document.getElementById("displaypopup").hidden = true;
}

//for quick add
function setQuickAdd(toHide1,toHide2,toShow1,toShow2){
document.getElementById(toHide1).hidden = true;
document.getElementById(toHide2).hidden = true;
document.getElementById(toShow1).hidden = false;
document.getElementById(toShow2).hidden = false;
}
function cancelQuickAdd(toShow1,toShow2,toHide1,toHide2){
document.getElementById(toShow1).hidden = false;
document.getElementById(toShow2).hidden = false;
document.getElementById(toHide1).hidden = true;
document.getElementById(toHide1).value = "";
document.getElementById(toHide2).hidden = true;
}