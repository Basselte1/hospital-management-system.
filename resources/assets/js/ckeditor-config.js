import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export const editorConfig = {
    language: 'fr',
    toolbar: {
        items: [
            'heading', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'alignment', '|',
            'numberedList', 'bulletedList', '|',
            'outdent', 'indent', '|',
            'link', 'insertTable', 'blockQuote', 'horizontalLine', '|',
            'undo', 'redo'
        ],
        shouldNotGroupWhenFull: true
    },
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Titre 4', class: 'ck-heading_heading4' }
        ]
    },
    fontSize: {
        options: [
            9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72
        ]
    },
    fontFamily: {
        options: [
            'default',
            'Arial, Helvetica, sans-serif',
            'Courier New, Courier, monospace',
            'Georgia, serif',
            'Lucida Sans Unicode, Lucida Grande, sans-serif',
            'Tahoma, Geneva, sans-serif',
            'Times New Roman, Times, serif',
            'Trebuchet MS, Helvetica, sans-serif',
            'Verdana, Geneva, sans-serif'
        ]
    },
    table: {
        contentToolbar: [
            'tableColumn', 'tableRow', 'mergeTableCells',
            'tableProperties', 'tableCellProperties'
        ]
    },
    link: {
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Ouvrir dans un nouvel onglet',
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        }
    }
};

export default ClassicEditor;