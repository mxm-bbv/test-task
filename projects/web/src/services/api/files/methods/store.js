export const store = async (file, currentName) => {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('name', currentName);

    const response = await fetch('https://api.live.local/api/v1/file/store', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        body: formData,
    });

    if (response.ok) {
        console.log('File saved:', currentName);
    } else {
        console.error('Error saving file:', response.statusText);
    }
}