function validateFormpw(form)
{
 var password = document.getElementById('pwd').value;
        var confirmPassword = document.getElementById('cfmpwd').value;
        if (password != confirmPassword)
        {
            alert("Passwords do not match.");
            return false;
        }
        return true;
}   

 function validate(form)
 {
        var gender = form.querySelectorAll('input[name="gender"]:checked');
        if (!gender.length) {
            alert('You must select male or female');
            return false;
     
        }
        return true;
 }
    
function callOut(form)
{
        validate(form);
        validateFormpw(form);
}