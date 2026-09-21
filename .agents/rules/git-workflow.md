# Git & Deployment Workflow Rule

- **No Automatic Commit/Push:** Never automatically run `git commit`, `git push`, or trigger remote deployment after editing files (including UI/template/CSS edits).
- **Explicit User Consent Required:** Only perform git commits and pushes when the user explicitly requests it (e.g., "commit ini", "push ke github", "sinkronkan ke server", "deploy").
- **Local Testing First:** Keep changes local so the user can test on `http://localhost:8000` before shipping to production VPS.
- **Code-First UI Customization:** Changes to `.tpl` and `.css` files can be done directly in code. When deployed, template cache is automatically flushed on VPS so edits appear immediately without GUI interaction.
