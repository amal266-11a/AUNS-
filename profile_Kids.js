function showStatus(message, type) {
    const statusElement = document.getElementById('statusMessage');
    
    statusElement.textContent = message;
    statusElement.className = `status-message show ${type}`;
    
    setTimeout(() => {
        statusElement.classList.remove('show');
    }, 3000); 
}

async function saveProfile(event) {
    event.preventDefault(); 
    
    const childName = document.getElementById('childName').value.trim();
    const childAge = document.getElementById('childAge').value.trim();
    const parentName = document.getElementById('parentName').value.trim();

    if (!childName || !childAge || !parentName) {
        showStatus("Please fill in all fields.", 'error');
        return;
    }

    const profileData = {
        parentId: 1, 
        childName: childName,
        childAge: parseInt(childAge),
        parentName: parentName 
    };
    
    try {
        const response = await fetch('save_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(profileData)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showStatus("Child added successfully!", 'success');
            
            setTimeout(() => {
                window.location.href = "profile.html"; 
            }, 1500);

            document.getElementById('childName').value = '';
            document.getElementById('childAge').value = '';
            
        } else {
            showStatus(result.message || "Failed to add child.", 'error');
        }
    } catch (error) {
        console.error("Error saving profile:", error);
        showStatus("Connection error while saving.", 'error');
    }
}

async function loadProfileData() {
    const parentNameInput = document.getElementById('parentName');

    try {
        const response = await fetch('get_profile.php', { method: 'GET' });

        const result = await response.json();

        if (result.success && result.data) {
            const data = result.data;
            parentNameInput.value = data.parent_name || ''; 
        } else if (!result.success && result.message) {
             showStatus(result.message, 'error');
        }
    } catch (error) {
        console.error("Error loading profile data:", error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadProfileData(); 
    
    const saveButton = document.getElementById('saveButton');
    if (saveButton) {
        saveButton.addEventListener('click', saveProfile);
    } else {
        console.error("Save button not found.");
    }
});