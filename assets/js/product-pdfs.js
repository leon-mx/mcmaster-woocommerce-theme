/**
 * Product PDF Management for Admin
 */

(function($) {
    'use strict';

    let pdfFiles = [];
    let mediaUploader;

    $(document).ready(function() {
        initPdfManagement();
        loadExistingFiles();
    });

    /**
     * Initialize PDF management
     */
    function initPdfManagement() {
        // Upload button click handler
        $('.upload-pdf-button').on('click', function(e) {
            e.preventDefault();
            openMediaUploader();
        });

        // Remove file handlers (delegated events)
        $(document).on('click', '.remove-pdf-file', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            removePdfFile(index);
        });
    }

    /**
     * Load existing PDF files
     */
    function loadExistingFiles() {
        const existingData = $('#_product_pdfs').val();
        
        if (existingData) {
            try {
                pdfFiles = JSON.parse(existingData);
                updateFilesList();
            } catch (e) {
                console.error('Error parsing existing PDF data:', e);
                pdfFiles = [];
            }
        }
    }

    /**
     * Open WordPress media uploader
     */
    function openMediaUploader() {
        // If the media uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Create the media uploader
        mediaUploader = wp.media({
            title: 'Select PDF Files',
            button: {
                text: 'Add Files'
            },
            multiple: true,
            library: {
                type: ['application/pdf']
            }
        });

        // Handle file selection
        mediaUploader.on('select', function() {
            const selection = mediaUploader.state().get('selection');
            
            selection.each(function(attachment) {
                const attachmentData = attachment.toJSON();
                
                // Only add PDF files
                if (attachmentData.mime === 'application/pdf') {
                    addPdfFile({
                        id: attachmentData.id,
                        name: attachmentData.filename || attachmentData.title,
                        title: attachmentData.title,
                        url: attachmentData.url,
                        size: formatFileSize(attachmentData.filesizeInBytes || 0)
                    });
                }
            });
        });

        mediaUploader.open();
    }

    /**
     * Add PDF file to the list
     */
    function addPdfFile(fileData) {
        // Check if file already exists
        const existingIndex = pdfFiles.findIndex(file => file.id === fileData.id);
        
        if (existingIndex === -1) {
            pdfFiles.push(fileData);
            updateFilesList();
            updateHiddenField();
            showNotification('PDF file added successfully', 'success');
        } else {
            showNotification('This file has already been added', 'warning');
        }
    }

    /**
     * Remove PDF file from the list
     */
    function removePdfFile(index) {
        if (index >= 0 && index < pdfFiles.length) {
            const fileName = pdfFiles[index].name;
            pdfFiles.splice(index, 1);
            updateFilesList();
            updateHiddenField();
            showNotification(`File "${fileName}" removed successfully`, 'success');
        }
    }

    /**
     * Update the files list display
     */
    function updateFilesList() {
        const $filesList = $('#pdf-files-list');
        
        if (pdfFiles.length === 0) {
            $filesList.html('<p style="color: #666; font-style: italic;">No PDF files added yet.</p>');
            return;
        }

        let html = '<div class="pdf-files-grid">';
        
        pdfFiles.forEach((file, index) => {
            html += `
                <div class="pdf-file-item" data-index="${index}">
                    <div class="pdf-file-info">
                        <div class="pdf-icon">📄</div>
                        <div class="pdf-details">
                            <div class="pdf-name" title="${escapeHtml(file.name)}">${escapeHtml(file.title || file.name)}</div>
                            <div class="pdf-size">${escapeHtml(file.size)}</div>
                        </div>
                    </div>
                    <div class="pdf-file-actions">
                        <a href="${escapeHtml(file.url)}" target="_blank" class="button button-small" title="Preview">👁️</a>
                        <button type="button" class="button button-small remove-pdf-file" data-index="${index}" title="Remove">🗑️</button>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        
        $filesList.html(html);
    }

    /**
     * Update hidden field with JSON data
     */
    function updateHiddenField() {
        const jsonData = JSON.stringify(pdfFiles);
        $('#_product_pdfs').val(jsonData);
    }

    /**
     * Format file size
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Escape HTML
     */
    function escapeHtml(unsafe) {
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    /**
     * Show notification
     */
    function showNotification(message, type) {
        // Create notification element
        const $notification = $(`
            <div class="pdf-notification pdf-notification-${type}">
                <span class="notification-icon">${type === 'success' ? '✅' : type === 'warning' ? '⚠️' : '❌'}</span>
                <span class="notification-message">${escapeHtml(message)}</span>
            </div>
        `);

        // Add to page
        $('#pdf-upload-section').prepend($notification);

        // Auto-remove after 3 seconds
        setTimeout(() => {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

})(jQuery);

// Add CSS styles for admin interface
const pdfAdminCSS = `
<style>
.pdf-files-grid {
    display: grid;
    gap: 10px;
    margin-top: 10px;
}

.pdf-file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.pdf-file-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.pdf-icon {
    font-size: 24px;
}

.pdf-details {
    flex: 1;
}

.pdf-name {
    font-weight: 500;
    color: #333;
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pdf-size {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}

.pdf-file-actions {
    display: flex;
    gap: 5px;
}

.pdf-file-actions .button {
    min-width: auto;
    padding: 2px 6px;
    font-size: 12px;
    line-height: 1.2;
}

.pdf-notification {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    margin-bottom: 10px;
    border-radius: 4px;
    font-size: 13px;
}

.pdf-notification-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.pdf-notification-warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.pdf-notification-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.notification-icon {
    font-size: 14px;
}

#pdf-upload-section .button.upload-pdf-button {
    background: #0073aa;
    border-color: #006799;
    color: white;
    padding: 6px 12px;
    font-size: 13px;
}

#pdf-upload-section .button.upload-pdf-button:hover {
    background: #005a87;
    border-color: #004a6b;
}

/* Responsive adjustments */
@media (max-width: 782px) {
    .pdf-file-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .pdf-file-actions {
        align-self: flex-end;
    }
}
</style>
`;

// Inject CSS
document.addEventListener('DOMContentLoaded', function() {
    document.head.insertAdjacentHTML('beforeend', pdfAdminCSS);
});