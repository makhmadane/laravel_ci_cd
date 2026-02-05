pipeline {
    agent any

    environment {
        IMAGE_NAME = "laravel-cd-ci-image"
        CONTAINER_NAME = "laravel-cd-ci-container"
    }

    stages {

        stage('Checkout') {
            steps {
                echo '📥 Checkout du code...'
                git branch: 'main',
                    credentialsId: 'github-credentials',
                    url: 'https://github.com/makhmadane/laravel_ci_cd.git'
            }
        }

        stage('Install deps / setup') {
            steps {
                echo '📦 Installation des dépendances Laravel...'
                sh '''
                composer install --no-interaction --prefer-dist
                cp .env.example .env || true
                php artisan key:generate
                php artisan config:clear
                '''
            }
        }

        stage('Run Tests') {
            steps {
                echo '🧪 Exécution des tests...'
                sh '''
                php artisan test
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                echo '🔍 Analyse SonarQube...'
                script {
                    def scannerHome = tool 'SonarScanner'
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }

        stage('Quality Gate') {
            steps {
                echo '🚦 Vérification Quality Gate...'
                timeout(time: 5, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                echo '🐳 Build image Docker...'
                sh '''
                docker build -t $IMAGE_NAME .
                '''
            }
        }

        stage('Deploy') {
            steps {
                echo '🚀 Déploiement du container...'
                sh '''
                docker rm -f $CONTAINER_NAME || true
                docker run -d -p 8000:8000 --name $CONTAINER_NAME $IMAGE_NAME
                '''
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline exécuté avec succès (Quality Gate respecté)'
        }

        failure {
            echo '❌ Pipeline échoué (tests ou Quality Gate KO)'
        }
    }
}
