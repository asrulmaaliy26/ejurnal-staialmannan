# Git & Deployment Workflow Rule

- **No Automatic Commit/Push:** Never automatically run `git commit`, `git push`, or trigger remote deployment after editing files.
- **Explicit User Consent Required:** Only perform git commits and pushes when the user explicitly requests it (e.g., "commit ini", "push ke github", "sinkronkan ke server").
- **Local Testing First:** Keep changes local so the user can test on `http://localhost:8000` before shipping to production VPS.
