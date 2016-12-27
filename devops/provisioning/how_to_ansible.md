
# Install Requirements from file

    ansible-galaxy install -r requirements.yml
    
# Run playbook

    ansible-playbook -i hosts index.yml


# Install package

    ansible-galaxy install --roles-path ./roles {{ PACKAGE_NAME }}
