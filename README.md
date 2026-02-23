# School Management System – CI Repository

## CI Pipeline Overview
1. Checkout source code
2. Generate image tag (Jenkins build number)
3. Build Docker image
4. Authenticate to AWS ECR
5. Push image to ECR
6. Update image tag in CD repository
____

## Docker & Image Management
- Production-ready Dockerfile
- Optimized image build
- Automated image tagging
- AWS ECR integration
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
- Jenkins
- AWS CLI
- IAM Role with ECR permissions
____

## Purpose
- Automate Docker build & push
- Manage image versioning
- Integrate Jenkins with AWS ECR
- Maintain clean CI/CD separation