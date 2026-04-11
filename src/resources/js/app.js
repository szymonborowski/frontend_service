import './bootstrap';
import { marked } from 'marked';
import DOMPurify from 'dompurify';
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';
import hljs from 'highlight.js/lib/core';
import php        from 'highlight.js/lib/languages/php';
import javascript from 'highlight.js/lib/languages/javascript';
import typescript from 'highlight.js/lib/languages/typescript';
import bash       from 'highlight.js/lib/languages/bash';
import css        from 'highlight.js/lib/languages/css';
import sql        from 'highlight.js/lib/languages/sql';
import python     from 'highlight.js/lib/languages/python';
import json       from 'highlight.js/lib/languages/json';
import xml        from 'highlight.js/lib/languages/xml';
import 'highlight.js/styles/github-dark.min.css';

hljs.registerLanguage('php', php);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('typescript', typescript);
hljs.registerLanguage('bash', bash);
hljs.registerLanguage('css', css);
hljs.registerLanguage('sql', sql);
hljs.registerLanguage('python', python);
hljs.registerLanguage('json', json);
hljs.registerLanguage('xml', xml);
hljs.registerLanguage('html', xml);

marked.use({ breaks: true, gfm: true });

window.renderMarkdown = (text) => {
    const raw = marked.parse(text ?? '');
    return DOMPurify.sanitize(raw, {
        ALLOWED_TAGS: ['p', 'br', 'strong', 'em', 'a', 'ul', 'ol', 'li', 'code', 'pre', 'blockquote', 'h1', 'h2', 'h3'],
        ALLOWED_ATTR: ['href', 'target', 'rel'],
        FORCE_BODY: true,
    });
};

window.EasyMDE = EasyMDE;

document.addEventListener('DOMContentLoaded', () => hljs.highlightAll());
