# School Management System – CI Repository

This repository represents the CI layer of the School Management System DevOps architecture.

## It is responsible for:
- Validating application code
- Building Docker images
- Performing security scans
- Pushing images to private Amazon ECR
- Updating the CD repository (GitOps flow)
  
CI is fully automated using GitHub Actions.
____

## ⚙️ CI Pipeline Overview (GitHub Actions)

On every push to the main branch, the workflow performs:
	1.	Checkout source code
	2.	Setup PHP environment
	3.	Install Composer dependencies
	4.	Validate composer.json
	5.	Run PHP lint (syntax validation)
	6.	Execute PHPUnit tests (if present)
	7.	Generate image tag (GitHub run number)
	8.	Authenticate to AWS using OIDC
	9.	Build Docker image
	10.	Scan image using Trivy (security scan)
	11.	Push image to private Amazon ECR
	12.	Update image tag in CD repository
____

## 🔄 CI Flow

```
Code Push
   ↓
GitHub Actions
   ↓
Build & Test
   ↓
Docker Build
   ↓
Trivy Scan
   ↓
Push to ECR
   ↓
Update CD Repo
   ↓
ArgoCD Sync
```
____

## 🧪 Code Quality & Testing

The pipeline includes validation and testing stages:
- Composer dependency validation
- PHP syntax checking (lint)
- PHPUnit execution (if tests exist)
- Docker image vulnerability scanning (Trivy)

If any validation, test, or security scan fails, the pipeline stops immediately.

⸻

## 🔐 Secure Authentication Model (OIDC)

GitHub Actions authenticates to AWS using:
- OpenID Connect (OIDC)
- Temporary IAM role assumption
- No static AWS credentials
- Least-privilege IAM permissions

Authentication is handled using:
```
aws-actions/configure-aws-credentials
```
This eliminates the need for long-lived AWS access keys.
____

## ECR Repository:
```
731628759499.dkr.ecr.us-east-1.amazonaws.com/school-management-system:<build-number>
```

## Example:
```
731628759499.dkr.ecr.us-east-1.amazonaws.com/school-management-system:42
```
____

## Repository Structure
```
├── app/
├── public/
├── assets/
├── vendor/
├── .github/workflows/ci.yaml
├── Dockerfile
├── docker-compose.yml
├── docker-compose.prod.yml
├── Jenkinsfile
└── .env.example
```
____

## Requirements
- PHP 8.x
- Composer
- Docker
- GitHub Actions
- AWS IAM Role configured for OIDC
- Amazon ECR repository
- OIDC provider configured in AWS
____

## Purpose
- Automate application validation and testing
- Secure Docker image build & push
- Eliminate static cloud credentials
- Maintain automated image versioning
- Enable GitOps-based deployment via ArgoCD
- Provide production-grade CI architecture
