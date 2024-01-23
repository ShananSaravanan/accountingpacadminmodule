

//global functions -> for common access
var OriginalData = [];
var EditIndex = [];
var duration;
function removeData(index){
    OriginalData.splice(index,1);
    if(OriginalData.length == 0){
        document.getElementById("update-btn").hidden = true;
    }
}

function exportToExcel() {
    // Get table headers dynamically
    const headers = Array.from(document.querySelectorAll('#edit-box table th')).map(th => th.textContent.trim());

    // Get table data
    const data = Array.from(document.querySelectorAll('#edit-box table tr')).map(row => {
        const rowData = Array.from(row.querySelectorAll('td')).map(td => {
            const inputElement = td.querySelector('input');
            return inputElement ? inputElement.value.trim() : '';
        });
        return Object.fromEntries(headers.map((header, index) => [header, rowData[index]]));
    });
    console.log('Headers:', headers);
    console.log('Data:', data);

    // Function to escape CSV value
    function escapeCSVValue(value) {
        return `"${String(value).replace(/"/g, '""')}"`;
    }

   // Function to convert data to CSV
// Function to convert data to CSV
function convertToCSV(data) {
    const filteredData = data.map(row => {
        const filteredRow = {};
        Object.keys(row).forEach(key => {
            if (key !== 'Actions' && row[key] !== undefined && row[key] !== 'undefined') {
                filteredRow[key] = row[key];
            }
        });
        return Object.keys(filteredRow).length > 0 ? filteredRow : null;
    }).filter(row => row !== null);

    const rows = [headers.slice(0, -1).map(escapeCSVValue).join(',')];  // Remove the last header
    for (const row of filteredData) {
        const values = headers.slice(0, -1).map(header => escapeCSVValue(row[header]));  // Remove the last column data
        rows.push(values.join(','));
    }
    return rows.join('\n');
}

    // Function to trigger download
    function downloadCSV(csv) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'exported_data.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Convert data to CSV and trigger download
    const csvData = convertToCSV(data);
    console.log('CSV Data:', csvData);
    downloadCSV(csvData);
}
function navigateToSelectedOption(selectElement) {
    if (selectElement.value) {
        window.location = selectElement.value;
    }
}
  // Attach the performSearch function to the search button click event

function ReadToWrite(data){
    for(var x = 0; x < data.length; x++) {
        data[x].readOnly = false;
        data[x].hidden = false;
    }
    data[0].readOnly = true
}
function setEditValues(){
    var arrayAsString = JSON.stringify(EditIndex);
    document.getElementById('rowcount').value = arrayAsString;
    
}
function storeIndex(index){
    EditIndex.push(index);
    setEditValues();
}
function removeIndex(index){
    EditIndex.splice(index,1);
    setEditValues();
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
    var data = document.getElementById(userId).querySelectorAll(".form-control");
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
    if(actionFrom == "add"){
        input = document.getElementById("imageinput2");
        preview = document.getElementById("imagepreview2"); 
    }
    else{
    var rowid = event.target.value;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    var input = data[8];
    var preview = data[7]; 
    
    if(actionFrom == "firm"){
        input = data[16];
        preview = data[15];
    } 
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
        
      if (selectElement.options[i].value == value) {
        
        return i;
      }
    }
    // If the value is not found, you may handle it accordingly (e.g., set a default index)
    return 0;
}
  function validateAmount(input) {
    // Regular expression pattern for 0.00 format
    var pattern = /^\d+(\.\d{0,2})?$/;

    // Check if the entered value matches the pattern
    if (!pattern.test(input.value)) {
        // If not, remove the last entered character
        input.value = input.value.slice(0, -1);
    }
}
//page specific functions

//for editing
function userEditMode(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var rowElement = event.target.closest('tr');
    var userId = event.target.parentNode.parentNode.id;
    var data = rowElement.querySelectorAll(".form-control");
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
function businessEdit(event, rownum) {
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var rowElement = event.target.closest('tr'); // Find the closest 'tr' parent element

    if (rowElement) {
        var data = rowElement.querySelectorAll(".form-control");

        var business = {
            id: data[0].value,
            name: data[1].value,
            businessType: data[2].value,
            businesscontact: data[4].value,
            businessemail: data[5].value,
        }

        OriginalData.push(business);
        ReadToWrite(data);
        setHidden(data, [2, 6, 7, 9, 10]);
        var bTypeName = data[2].name;
        data[3].selectedIndex = getSelectedIndexByValue(data[3], bTypeName);
    }
}

function firmEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    
    var firm={
        id: data[0].value,
        name:data[1].value,
        owner:data[2].value,
        firmType:data[4].value,
        afno:data[6].value,
        ssmno:data[7].value,
        firmcontact:data[8].value,
        firmemail:data[9].value,
        firmaddress:data[10].value,
        userlimit:data[12].value
    }
    OriginalData.push(firm);
    ReadToWrite(data);
    setHidden(data,[2,4,10,14,17,18]);
    setVisible(data,[3,5,11,16,19]);
    var firmtype = data[4].name;
    var ownername = data[2].name;
    var firmaddress = data[10].name;
    data[4].selectedIndex  = getSelectedIndexByValue(data[4],firmtype);
    data[3].selectedIndex  = getSelectedIndexByValue(data[3],ownername);
    data[10].selectedIndex  = getSelectedIndexByValue(data[10],firmaddress);
}
function roleEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var role={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(role);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function bTypeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var businessType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(businessType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function HcodeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var hcode={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(hcode);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function addressTypeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var aType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(aType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function firmTypeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var firmType={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(firmType);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function postOfficeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var postOffice={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(postOffice);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function stateCodeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var stateCode={
        id: data[0].value,
        name:data[1].value
    }
    OriginalData.push(stateCode);
    ReadToWrite(data);
    setHidden(data,[2,4]);
    setVisible(data,[3]);
}
function packageEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var pac={
        id: data[0].value,
        code:data[1].value,
        name:data[2].value,
        userlimit:data[3].value
    }
    OriginalData.push(pac);
    ReadToWrite(data);
    setHidden(data,[4,6]);
    setVisible(data,[5]);
}
function BaseEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
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
function firmUserEdit(event,rownum){
    storeIndex(rownum);
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var firmuser={
        id: data[0].value,
        firmname:data[1].value,
        user:data[3].value,
        miano:data[5].value,                                                                                                    
        pcno:data[6].value
    }
    OriginalData.push(firmuser);
    ReadToWrite(data);
    setHidden(data,[1,3,7,9]);
    setVisible(data,[2,4,8]);
    var firmname = data[1].name;
    var user = data[3].name;
    if(firmname == 'No firm'){
        data[2].selectedIndex = 0;
    }
    else{
    data[2].selectedIndex = getSelectedIndexByValue(data[2], firmname);
    }
    data[4].selectedIndex = getSelectedIndexByValue(data[4], user);
    
}
function transactionEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
    var trans={
        id: data[0].value,
        no:data[1].value,
        name:data[2].value,
        amount:data[3].value,
        fpxid: data[4].value,
        fpxchecksum:data[5].value,
        bankid:data[6].value,
        time:data[7].value,
        status:data[8].value,
    }
    OriginalData.push(trans);
    ReadToWrite(data);
    setHidden(data,[9,11]);
    setVisible(data,[10]);
}
function bUserEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var eventid = event.target.parentNode.parentNode.id;
    var data = document.getElementById(eventid).querySelectorAll(".form-control");
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
function postCodeEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
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
function financialRecordEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    var dateValue = data[7].value;
    var frecord ={
        id: data[0].value,
        businessname:data[1].value,
        category:data[3].value,
        amount:data[5].value,
        description:data[6].value,
        recordedtime:data[7].value
    }
    
    OriginalData.push(frecord);
    ReadToWrite(data);
    setHidden(data,[1,3,7,9,10]);
    setVisible(data,[2,4,8,11]);
    var businessname = data[1].name;
    var category = data[3].name;
    data[2].selectedIndex  = getSelectedIndexByValue(data[2],businessname);
    data[4].selectedIndex  = getSelectedIndexByValue(data[4],category);
    
    var dateinput = document.getElementById('recordtime');
    dateinput.value = dateValue;
}

function addressEdit(event,rownum){
    
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
   
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
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
function subscriptionEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    var subscription ={
        id: data[0].value,
        user:data[1].value,
        package:data[3].value,
        packagecode:data[5].value,
        datefrom:data[6].value,
        dateto:data[8].value,
        transaction:data[10].value,
        amount:data[12].value,
        bank:data[13].value,
        status:data[14].value,
        date:data[16].value
    }
    OriginalData.push(subscription);
    ReadToWrite(data);
    setHidden(data,[1,3,6,8,10,14,16,18,20]);
    setVisible(data,[2,4,5,7,9,11,15,17,19]);
    data[9].readOnly = true;
    data[5].readOnly = true;
    data[12].readOnly = true;
    data[13].readOnly = true;
    var user = data[1].name;
    data[16].readOnly = true;
    var transaction = data[10].name;
    var status = data[14].name;
    handleUserChange(data[2],event);
    
    data[2].selectedIndex  = getSelectedIndexByValue(data[2],user);
    data[11].selectedIndex  = getSelectedIndexByValue(data[11],transaction);
    data[15].selectedIndex  = getSelectedIndexByValue(data[15],status);
    handleTransactionChange(data[11],event);
    handleStatusChange(data[14],event);
    var datefrom = data[7];
    datefrom.value = data[6].value;

    var dateto = data[9];
    dateto.value = data[8].value;
    
}
function AssignmentEdit(event,rownum){
    storeIndex(rownum);
    document.getElementById('update-btn').hidden = false;
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    
    var assignment ={
        id: data[0].value,
        buser:data[1].value,
        firmuser:data[4].value,
        datevalidfrom:data[7].value,
        datevalidto:data[9].value,
        accesscode:data[11].value,
        status:data[12].value
    }
    OriginalData.push(assignment);
    ReadToWrite(data);
    setHidden(data,[1,4,7,9,12,14,16]);
    setVisible(data,[2,5,8,10,13,15]);
    data[10].readOnly = true;               
    data[11].readOnly = true;
    var buser = data[1].name;
    var firmuser = data[4].name;
    var status = data[12].name;
    handleSubscriptionUserChange(data[2],event,"business");
    handleSubscriptionUserChange(data[5],event,"firm");
    data[2].selectedIndex  = getSelectedIndexByValue(data[2],buser);
    data[5].selectedIndex  = getSelectedIndexByValue(data[5],firmuser);
    data[13].selectedIndex  = getSelectedIndexByValue(data[13],status);

    var datefrom = data[8];
    datefrom.value = data[7].value;

    var dateto = data[10];
    dateto.value = data[9].value;
    
}
function handleStateCodeChange(element,event) {
    var selectedStateCode = element.value;
    
    var rowcount = element.id.replace('stateCode', '');
    if(event != "Add"){
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
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
function handleUserChange(element,event) {
    var selecteduser = element.value;
    if(event != "Add"){
    var rowcount = element.id.replace('user', '');
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    }
    // Make an AJAX request to fetch package based on the selected state code
    axios.get('/get-packages/' + selecteduser)
        .then(function(response) {
            var packagesArray = response.data.packages;
            
            
            
            // Update the content of the existing 'postOffice' dropdown
            var pacakagedropdown = document.getElementById('packageOptions' + rowcount);
            
            if(event == "Add"){
                pacakagedropdown = document.getElementById('new-package-id');
            }
            // Clear existing options
            pacakagedropdown.innerHTML = '';
            
            packagesArray.forEach(packageData => { 
                var option = document.createElement('option');
                option.text = packageData.package.name + ' for ' +packageData.duration + ' days';
                
                option.value = packageData.id;
                pacakagedropdown.add(option);
            });
            var element;
            if(event == "Add"){
                element = document.getElementById('new-package-id');
                
            }
            else{
                var pac = data[3].name;
                data[4].selectedIndex  = getSelectedIndexByValue(data[4],pac);
                element = data[4];
            }
            handlePackageChange(element,event);
        })
        .catch(function(error) {
            console.error('Error fetching packages', error);
        });
        
}  
function handleSubscriptionUserChange(element,event,usertype) {
    
    var selecteduser = element.value;
    if(event != "Add"){
    var rowcount = element.id.replace('user', '');
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    bcode = data[3];
    duration = data[17];
    fcode = data[6];
    accesscode = data[11];
    }
    else{
        rowcount = event;
        bcode = document.getElementById('new-buser-code');
        fcode = document.getElementById('new-fuser-code');
        duration = document.getElementById('new-duration');
        accesscode = document.getElementById('new-access-code');
    }
    
    // Make an AJAX request to fetch package based on the selected state code
    axios.get('/get-subscriptiondetails/' + selecteduser)
        .then(function(response) {
            var packageData = response.data;
            console.log(response.data);
                if(usertype =="business"){
                    bcode.value = packageData.code;
                    
                    duration.value = packageData.duration;
                }

                if(usertype =="firm"){
                    fcode.value = packageData.code;
                    accesscode.value = bcode.value+fcode.value;
                    
                }
                
                changeValidDateTimeTo(rowcount);
                
        })
        .catch(function(error) {
            console.error('Error fetching subscription details', error);
              
        });
        
}  
function handlePackageChange(element,event) {
    var selectedpackage = element.value;
    if(event != "Add"){
    var rowcount = element.id.replace('packageOptions', '');
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    dateelement = data[21];
    var textBox = document.getElementById('code'+rowcount);
    }
    else{
        textBox = document.getElementById('new-package-code');
        dateelement = document.getElementById('new-duration');
        rowcount = event;
    }
    // Make an AJAX request to fetch package based on the selected state code
    axios.get('/get-code/' + selectedpackage)
        .then(function(response) {
            var alldata = response.data;
            
            var code = alldata.code;
            dateelement.value = alldata.duration;
            
            changeValidDateTimeTo(rowcount); 
            textBox.value = code;
            /*if(event == "Add"){
                var element = document.getElementById('new-address-postoffice');
                handlePackageChange(element,event);
            }
            else{
                handlePackageChange(data[10],2);
            }*/
            
        })
        .catch(function(error) {
            console.error('Error fetching code', error);
        });
        
}
function handleTransactionChange(element,event) {
    var selectedtransaction = element.value;
    if(event != "Add"){
    var rowcount = element.id.replace('transactionOptions', '');
    var userId = event.target.parentNode.parentNode.id;
    var data = document.getElementById(userId).querySelectorAll(".form-control");
    bankelement = data[12];
    amountelement = data[13];
    }
    else{
        bankelement = document.getElementById('new-bank');
        amountelement = document.getElementById('new-amount');
    }
    // Make an AJAX request to fetch package based on the selected state code
    axios.get('/get-transactiondetails/' + selectedtransaction)
        .then(function(response) {
            var alldata = response.data;
            
            bankelement.value = alldata.bank;
            amountelement.value = alldata.amount;
            
        })
        .catch(function(error) {
            console.error('Error fetching transaction details', error);
        });
        
}

function changeValidDateTimeTo(rowcount){
    if(rowcount == "Add"){
        var element = document.getElementById('new-date-valid-to');
        var days = parseInt(document.getElementById('new-duration').value);
        var dayfrom = document.getElementById('new-date-valid-from').value;
        
    }
    else{
        var element = document.getElementById('DateValidTo' + rowcount);
        var days = parseInt(document.getElementById('duration' + rowcount).value);
        var dayfrom = document.getElementById('DateValidFrom' + rowcount).value;
    }
    

// Convert 'dayfrom' to a Date object
var fromDate = new Date(dayfrom);

// Check if 'fromDate' is a valid date
if (isNaN(fromDate.getTime())) {
    console.error('Invalid fromDate:', fromDate);
    // Handle the error as needed
} else {
    // Add 'days' to the date
    var toDate = new Date(fromDate);
    toDate.setDate(fromDate.getDate() + days);

    // Check if 'toDate' is a valid date
    if (!isNaN(toDate.getTime())) {
        // Format the date to set as the value of 'element'
        var formattedDate = toDate.toISOString().split('.')[0]; // Remove milliseconds part
        formattedDate = formattedDate.replace('T', ' '); // Replace 'T' with a space
        element.value = formattedDate;
    } else {
        console.error('Invalid toDate:', toDate);
        // Handle the error as needed
    }
}



}  
function handleStatusChange(element,event){
    if(event != "Add"){
        var rowcount = element.id.replace('status', '');
        var userId = event.target.parentNode.parentNode.id;
        var data = document.getElementById(userId).querySelectorAll(".form-control");
        displayelement = data[16];
        dateelement = data[17];
    }
    else{
        displayelement = document.getElementById('new-cancelleddate-text');
        dateelement = document.getElementById('new-cancelleddate');
    }
    
    if(element.value == "Cancelled"){
        displayelement.hidden = true;
        dateelement.hidden = false;
        
        
        if(displayelement.value == "UNCANCELLED"){
            displayelement.hidden = true;
            dateelement.hidden = false;
            var currentDate = new Date();
            // Format the current date as YYYY-MM-DDTHH:mm
            var formattedDate = currentDate.toISOString().slice(0, 16);
            dateelement.value = formattedDate;
        }
        else{
            dateelement.value = displayelement.value;
        }
    }
    
    if(element.value == "Active" || element.value == "Inactive"){
        displayelement.readOnly = true;
        displayelement.hidden = false;
        dateelement.hidden = true;
        displayelement.value = "UNCANCELLED";
    }
}
//for cancelling edit
function cancelUserEdit(event,rownum){
    removeIndex(rownum);
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
function cancelBusinessEdit(event,rownum){
    removeIndex(rownum);
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
function cancelFirmEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    data[2].value = OriginalData[index].owner;
    data[4].value = OriginalData[index].firmType;
    data[6].value = OriginalData[index].ssmno;
    data[8].value = OriginalData[index].firmcontact;
    data[9].value = OriginalData[index].firmemail;
    data[10].value = OriginalData[index].firmaddress;
    data[12].value = OriginalData[index].userlimit;
    
    
    setHidden(data,[3,5,11,15,16,19]);
    setVisible(data,[2,4,10,14,17,18]);
    removeData(index);
    data[15].src = "";
    data[16].value = "";
}
function cancelRoleEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelHcodeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelbTypeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function canceladdressTypeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelfirmTypeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelpostOfficeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelstateCodeEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].name;
    setHidden(data,[3]);
    setVisible(data,[2,4]);
    removeData(index);
}
function cancelPackageEdit(event,rownum){
    removeIndex(rownum);
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
function canceltransactionEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].no;
    data[2].value = OriginalData[index].name;
    data[3].value = OriginalData[index].amount;
    data[4].value = OriginalData[index].fpxid;
    data[5].value = OriginalData[index].fpxchecksum;
    data[6].value = OriginalData[index].bankid;
    data[7].value = OriginalData[index].time;
    data[8].value = OriginalData[index].status;
    setHidden(data,[10]);
    setVisible(data,[9,11]);
    removeData(index);
}
function cancelPackageBaseEdit(event,rownum){
    removeIndex(rownum);
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
function cancelfirmUserEdit(event,rownum) {
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].firmname;
    data[3].value = OriginalData[index].user;
    data[5].value = OriginalData[index].miano;
    data[6].value = OriginalData[index].pcno;
    setHidden(data,[2,4,8]);
    setVisible(data,[1,3,7,9]);
    removeData(index);
}
function cancelbUserEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].bName;
    data[2].value = OriginalData[index].uEmail;
    setHidden(data,[2,4,6]);
    setVisible(data,[1,3,5,7]);
    removeData(index);
}
function cancelpostCodeEdit(event,rownum){
    removeIndex(rownum);
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
function cancelfinancialRecordEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].businessname;
    data[3].value = OriginalData[index].category;
    data[5].value = OriginalData[index].amount;
    data[6].value = OriginalData[index].description;
    data[7].value = OriginalData[index].recordedtime;
    setHidden(data,[2,4,8,11]);
    setVisible(data,[1,3,7,9,10]);
    removeData(index);
}
function cancelAddressEdit(event,rownum){
    removeIndex(rownum);
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
function cancelsubscriptionEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].user;
    data[3].value = OriginalData[index].package;
    data[5].value = OriginalData[index].packagecode;
    data[6].value = OriginalData[index].datefrom;
    data[8].value = OriginalData[index].dateto;
    data[10].value = OriginalData[index].transaction;
    data[12].value = OriginalData[index].amount;
    data[13].value = OriginalData[index].bank;
    data[15].value = OriginalData[index].status;
    data[16].value = OriginalData[index].date;
    
    setHidden(data,[2,4,7,9,11,15,17,19]);
    setVisible(data,[1,3,6,8,10,14,16,18,20]);
    
    removeData(index);
}
function cancelAssignmentEdit(event,rownum){
    removeIndex(rownum);
    var data = getTableData(event);
    var index = cancelEditActions(event,data);
    
    data[0].value = OriginalData[index].id;
    data[1].value = OriginalData[index].buser;
    data[4].value = OriginalData[index].firmuser;
    data[7].value = OriginalData[index].datevalidfrom;
    data[9].value = OriginalData[index].datevalidto;
    data[11].value = OriginalData[index].accesscode;
    data[12].value = OriginalData[index].status;

    
    setHidden(data,[2,5,8,10,13,15]);
    setVisible(data,[1,4,7,9,12,14,16]);
    
    removeData(index);
}
  
//for popups
function showAddBox(){
    document.getElementById('myModal').style.display = 'block';
    
}
function showAddBoxForAjax(htmlelement) {
    document.getElementById('myModal').style.display = 'block';

    var element = document.getElementById(htmlelement);
    if(htmlelement == "new-address-state"){
        handleStateCodeChange(element,"Add");
    }
    else if(htmlelement == "new-buser"){
        handleSubscriptionUserChange(element,"Add","business");
        var element = document.getElementById("new-fuser");
        handleSubscriptionUserChange(element,"Add","firm");
    }
    else{
        handleUserChange(element,"Add");
        var element = document.getElementById('new-transaction');
        handleTransactionChange(element,"Add");
    }
}
function openAddBox() {
        document.getElementById('myModal').style.display = 'block';
    }

    function closeAddBox() {
        document.getElementById('myModal').style.display = 'none';
    }
// function closeAddBox() {
//     document.getElementById("add-box").hidden = true;
//     document.getElementById("edit-box").hidden = false;
//     document.getElementById("newBtn").hidden = false;
// }
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