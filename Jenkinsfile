pipeline {
  agent any

  environment {
    IMAGE_NAME = "ahmedlebshten/school_management_system"
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
          // Jenkins build number = auto increment
          env.IMAGE_TAG = env.BUILD_NUMBER
          echo "Image tag will be: ${env.IMAGE_TAG}"
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

    stage('Login to Docker Hub') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-credentials',
          usernameVariable: 'DOCKER_USER',
          passwordVariable: 'DOCKER_PASS'
        )]) {
          sh '''
            echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin
          '''
        }
      }
    }

    stage('Push Docker Image') {
      steps {
        sh """
          docker push ${IMAGE_NAME}:${IMAGE_TAG}
        """
      }
    }
  }

  post {
    success {
      echo "✅ Image pushed successfully: ${IMAGE_NAME}:${IMAGE_TAG}"
    }
    failure {
      echo "❌ CI failed"
    }
  }
}
