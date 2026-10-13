# Security Policy

## Supported Versions

| Version | Supported |
|---------|-----------|
| 1.x     | Yes       |
| < 1.0   | No        |

## Reporting a Vulnerability

Report vulnerabilities privately through
[GitHub's vulnerability reporting form](https://github.com/TypesetterIO/typesetter-cli/security/advisories/new).
Do not open a public issue.

Include the Typesetter CLI version, the PHP version or Docker image tag, and
the smallest config and content that reproduce the problem.

You will get a response within 7 days. Confirmed vulnerabilities are fixed in a
new release and published as a GitHub security advisory, crediting the reporter
unless they ask otherwise.

## Scope

This policy covers the `typesetter` command and the Docker image at
`ghcr.io/typesetterio/typesetter-cli`.

Typesetter treats the config, theme, and content it is given as trusted input
written by the book's author. The config file is executed as PHP. Behavior that
requires a malicious config, theme, or Markdown file is not a vulnerability.

Report vulnerabilities in the rendering engine to
[typesetterio/typesetter](https://github.com/TypesetterIO/typesetter/security/advisories/new).
Report vulnerabilities in other dependencies such as mPDF or Laravel Zero to
those projects, unless the problem is in how Typesetter CLI uses them.
