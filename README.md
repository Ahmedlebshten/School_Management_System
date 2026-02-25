# School Management System – CI Repository

## CI Pipeline Overview (GitHub Actions)

1. Checkout source code
2. Generate image tag (GitHub run number)
3. Configure AWS credentials using OIDC
4. Build Docker image
5. Authenticate to AWS ECR
6. Push image to ECR
7. Update image tag in CD repository (GitOps flow)

____

## CI Workflow

CI is fully automated using **GitHub Actions**.

Trigger:
Push to main branch
____

## Flow
Code Push → GitHub Actions → Docker Build → Push to ECR → Update Deployment YAML → ArgoCD Sync
____

## Docker & Image Management
- Production-ready Dockerfile
- Optimized image build
- Automated image tagging
- Secure authentication to AWS via OIDC (no static AWS keys)
- AWS ECR private repository integration
____

## Authentication Model (Secure OIDC)

GitHub Actions assumes an AWS IAM Role using:

- OpenID Connect (OIDC)
- Temporary credentials
- No long-lived access keys
- Least-privilege IAM permissions

ECR Login is handled automatically via:
aws-actions/configure-aws-credentials
____

## ECR Repository:
```
731628759499.dkr.ecr.us-east-1.amazonaws.com/school-management-system:<build-number>
```

## Example:
```
731628759499.dkr.ecr.us-east-1.amazonaws.com/school-management-system:42
```

## CI Flow
```
Code Push → Jenkins Build → Docker Build → Push to ECR → Update Deployment YAML
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

## ECR Authentication

#### Jenkins authenticates to ECR using:
```
aws ecr get-login-password --region us-east-1
```
#### Nodes are configured with:
```
AmazonEC2ContainerRegistryReadOnly
```

policy to allow pulling images from ECR.
____

## Requirements
- PHP 7.4+
- Composer
- Docker
- GitHub Actions
- Jenkins
- AWS CLI
- IAM Role with ECR permissions
- OIDC provider configured in AWS
____

## Purpose
- Automate Docker build & push
- Secure cloud authentication
- Manage image versioning automatically
- Integrate GitHub Actions with AWS ECR
- Enable GitOps deployment via ArgoCD
- Production-grade CI architecture