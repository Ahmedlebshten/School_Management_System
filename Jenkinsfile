pipeline {
  agent any

  environment {
    AWS_REGION  = "us-east-1"
    AWS_ACCOUNT = "731628759499"
    ECR_REPO    = "school-management-system"

    IMAGE_NAME  = "${AWS_ACCOUNT}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPO}"

    CD_REPO     = "https://github.com/Ahmedlebshten/School_Management_System_CD.git"
    DEPLOY_FILE = "school/school-deployment.yaml"
  }

  stages {

    stage('Checkout Source Code') {
      steps {
        checkout scm
      }
    }

    stage('Generate Image Tag') {
      steps {
        script {
          env.IMAGE_TAG = env.BUILD_NUMBER
          echo "Image tag: ${IMAGE_TAG}"
        }
      }
    }

    stage('Clone CD Repo') {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: 'github-credentials',
            usernameVariable: 'GIT_USER',
            passwordVariable: 'GIT_PASS'
          )
        ]) {
          sh """
            rm -rf cd-repo
            git clone https://${GIT_USER}:${GIT_PASS}@github.com/Ahmedlebshten/School_Management_System_CD.git cd-repo
          """
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        sh """
          docker build -t ${IMAGE_NAME}:${IMAGE_TAG} .
        """
      }
    }

    stage('ECR Login') {
      steps {
        sh """
          aws ecr get-login-password --region ${AWS_REGION} \
          | docker login --username AWS --password-stdin ${AWS_ACCOUNT}.dkr.ecr.${AWS_REGION}.amazonaws.com
        """
      }
    }

    stage('Push Docker Image') {
      steps {
        sh """
          docker push ${IMAGE_NAME}:${IMAGE_TAG}
        """
      }
    }

    stage('Update CD Repo (bump image tag)') {
      steps {
        sh """
          cd cd-repo
          test -f ${DEPLOY_FILE}

          sed -i "s|image: .*|image: ${IMAGE_NAME}:${IMAGE_TAG}|g" ${DEPLOY_FILE}

          git config user.email "jenkins@ci.local"
          git config user.name "jenkins"

          if git diff --quiet; then
            echo "No changes to commit"
          else
            git add ${DEPLOY_FILE}
            git commit -m "ci: bump image to ${IMAGE_TAG}"
            git push origin HEAD
          fi
        """
      }
    }
  }

  post {
    success {
      echo "✅ Image ${IMAGE_NAME}:${IMAGE_TAG} pushed to ECR and CD repo updated"
    }
    failure {
      echo "❌ CI failed"
    }
  }
}