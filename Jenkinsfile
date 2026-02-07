pipeline {
  agent any

  environment {
    DOCKERHUB_REPO = "ahmedlebshten/url-shortener"
    CD_REPO        = "https://github.com/Ahmedlebshten/School_Management_System.git"
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Clone CD repo & prepare tag') {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: 'github-credentials',
            usernameVariable: 'GIT_USER',
            passwordVariable: 'GIT_PASS'
          )
        ]) {
          script {
            sh '''
              set -e
              rm -rf cd-repo

              git clone https://${GIT_USER}:${GIT_PASS}@github.com/Ahmedlebshten/School_Management_System.git cd-repo
            '''

            // Read the current tag of image in deployment.yaml in ArgoCD-repo
            def line = sh(
              script: "cd cd-repo && grep 'image: ${DOCKERHUB_REPO}:' ${DEPLOY_FILE} | head -1",
              returnStdout: true
            ).trim()

            if (!line) {
              error "Could not find image line in ${DEPLOY_FILE}"
            }

            def currentTag = line.tokenize(':')[-1] as int
            env.IMAGE_TAG = (currentTag + 1).toString()

            echo "Current tag: ${currentTag}, new tag: ${env.IMAGE_TAG}"
          }
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        sh "docker build -t ${DOCKERHUB_REPO}:${IMAGE_TAG} ."
      }
    }

    stage('Docker Login') {
      steps {
        withCredentials([
          usernamePassword(
            credentialsId: 'dockerhub-credentials',
            usernameVariable: 'DH_USER',
            passwordVariable: 'DH_PASS'
          )
        ]) {
          sh 'echo $DH_PASS | docker login -u $DH_USER --password-stdin'
        }
      }
    }

    stage('Push Docker Image') {
      steps {
        sh "docker push ${DOCKERHUB_REPO}:${IMAGE_TAG}"
      }
    }
  }

  post {
    success { echo "Done: ${DOCKERHUB_REPO}:${IMAGE_TAG}" }
    failure { echo "Failed" }
  }
}
