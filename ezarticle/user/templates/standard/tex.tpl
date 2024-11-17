\documentclass[12pt]{article}
\usepackage[dvips]{graphics}
\usepackage[T1]{fontenc}
\usepackage{thumbpdf}
\usepackage[pdftex,
        colorlinks=true,
        urlcolor=rltblue,       % \href{...}{...} external (URL)
        filecolor=rltgreen,     % \href{...} local file
        linkcolor=rltred,       % \ref{...} and \pageref{...}
        pdftitle={Untitled},
        pdfauthor={{author_name}},
        pdfsubject={{article_name}},
        pdfkeywords={test testing testable},
        pagebackref,
        pdfpagemode=None,
        bookmarksopen=true]{hyperref}
\usepackage{color}
\definecolor{rltred}{rgb}{0.75,0,0}
\definecolor{rltgreen}{rgb}{0,0.5,0}
\definecolor{rltblue}{rgb}{0,0,0.75}
\topmargin = -70pt
\oddsidemargin = -50pt
\textwidth = 500pt
\textheight = 800pt

\begin{document}

\title{{article_name}}

\author{{author_name}}

\maketitle

\begin{abstract}
{article_intro}
\end{abstract}

{article_body}

\end{document}
