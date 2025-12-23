const API_URL = 'profile.php';
const LOGIN_PAGE_URL = 'LogIn.html'; 
const loginContainer = document.getElementById('login-container');
const signupContainer = document.getElementById('signup-container');
let children = []; 

function showLoginForm() {
    if (loginContainer && signupContainer) {
        loginContainer.style.display = 'block';
        signupContainer.style.display = 'none';
    }
}

function showSignupForm() {
    if (loginContainer && signupContainer) {
        loginContainer.style.display = 'none';
        signupContainer.style.display = 'block';
    }
}

async function checkLoginAndLoadData() {
    try {
        const parentResponse = await fetch(API_URL, { 
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        if (parentResponse.status === 401) {
            alert('Your session has expired or you are not logged in. Redirecting to login.');
            window.location.href = LOGIN_PAGE_URL;
            return;
        }

        const parentData = await parentResponse.json();

        if (parentData.success) {
            document.getElementById('user-name').value = parentData.user.name || '';
            document.getElementById('user-email').value = parentData.user.email || 'N/A';
            document.getElementById('pref-language-display').value = parentData.user.pref_language || 'English';

            const childrenResponse = await fetch('get_profile.php', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            const childrenResult = await childrenResponse.json();

            if (childrenResult.success) {
                children = childrenResult.data || []; 
                renderChildrenList();
            } else {
                 console.error('Children API Error:', childrenResult.message);
            }

        } else {
            console.error('Parent API Error:', parentData.message);
            alert('Error loading profile data: ' + parentData.message);
        }

    } catch (error) {
        console.error('Fetch error during data load:', error);
        alert('An unexpected error occurred while connecting to the server.');
    }
}

async function updatePersonalInfo() {
    const newName = document.getElementById('user-name').value;
    
    if (newName.trim() === "") {
        alert("Name cannot be empty!");
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name: newName, action: 'update_profile' }) 
        });
        
        const data = await response.json();

        if (response.ok && data.success) {
            alert('✅ Personal information updated successfully!');
        } else if (response.status === 401) {
             alert('Session expired. Please log in again.');
             window.location.href = LOGIN_PAGE_URL;
        } else {
            alert('Update failed: ' + (data.message || 'Unknown error'));
        }

    } catch (error) {
        console.error('Fetch error during update:', error);
        alert('A network error occurred while trying to update.');
    }
}

async function logoutUser() {
    const confirmed = confirm("Are you sure you want to log out?");
    if (confirmed) {
        try {
            await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'logout' }) 
            });
            
        } catch (error) {
            console.error('Logout network error:', error);
        }
        
        window.location.href = LOGIN_PAGE_URL;
    }
}

async function deleteChild(index) {
    const childToDelete = children[index];

    if (!childToDelete || !childToDelete.child_id) {
        alert("Error: Cannot find child ID.");
        return;
    }

    const confirmed = confirm(`Are you sure you want to delete child ${childToDelete.name}?`);

    if (confirmed) {
        try {
            const response = await fetch(API_URL, { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'delete_child', 
                    child_id: childToDelete.child_id 
                }) 
            });

            const data = await response.json();

            if (data.success) {
           
                children.splice(index, 1);
                renderChildrenList();
                alert(`Child ${childToDelete.name} has been deleted.`);
            } else {
                alert('Failed to delete: ' + data.message);
            }

        } catch (error) {
            console.error('Delete error:', error);
            alert('An error occurred while deleting.');
        }
    }
}

function renderChildrenList() {
    const listElement = document.getElementById('children-list');
    listElement.innerHTML = ''; 

    if (children.length === 0) {
        const messageItem = document.createElement('li');
        messageItem.textContent = 'No children added yet.';
        listElement.appendChild(messageItem);
    } else {
        children.forEach((childData, index) => {
            const listItem = document.createElement('li');
            
            listItem.innerHTML = `
                ${childData.name} - Age: ${childData.age}
                <button class="delete-btn" onclick="deleteChild(${index})">Delete</button>
            `; 
            listElement.appendChild(listItem);
        });
    }
}

function goToAddChildrenPage() {
 window.location.href = "AddProfileKids.html";
}

document.addEventListener('DOMContentLoaded', () => {
    checkLoginAndLoadData(); 

    const saveInfoButton = document.getElementById('saveInfoButton');
    if (saveInfoButton) {
        saveInfoButton.addEventListener('click', updatePersonalInfo);
    }

    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', logoutUser);
    }

    const addChildButton = document.getElementById('add-new-child-button');
    if (addChildButton) {
        addChildButton.addEventListener('click', goToAddChildrenPage);
    }
});