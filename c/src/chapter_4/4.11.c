#include <stdio.h>

int main(void)
{
	int number = 0;
	int rebmun = 0;
	int temp = 0;
	
	printf("\nEnter a positive integer:");
	scanf(" %d", &number);
	
	temp = number;
	
	do {
		rebmun = 10 * rebmun + temp % 10;
		temp = temp / 10;
	} while (temp);

	printf("\nThe number %d reversed is %d rebmun ehT\n", number, rebmun);
	return 0;
}
